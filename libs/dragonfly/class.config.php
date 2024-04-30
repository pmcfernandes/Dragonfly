<?php
namespace Impedro\Dragonfly;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\Mvc\Controller;

final class Config
{
    // Add your server hostname to the appropriate arrays. ($_SERVER['HTTP_HOST'])
    private $productionServers = array('/^your-domain\.com/');
    private $stagingServers = array();
    private $localServers = array('/^localhost/');

    // Singleton object. Leave $me alone.
    private static $me;

    public $dbOnError;
    public $dbEmailOnError = false;

    // Set authentication for cookie
    public $authDomain = "";
    public $authSalt;

    // Set email properties
    public $emailTo;

    /**
     * Constructor
     *
     */
    private function __construct()
    {
        $this->everywhere();
        $i_am_here = $this->whereAmI();

        if ($i_am_here == 'production') {
            $this->production();
            return;
        }
        if ($i_am_here == 'staging') {
            $this->staging();
            return;
        }
        if ($i_am_here == 'local') {
            $this->local();
            return;
        }

        die('<h1>Where am I?</h1> <p>You need to setup your server names in <code>class.config.php</code></p>
            <p><code>$_SERVER[\'HTTP_HOST\']</code> reported <code>' . $_SERVER['HTTP_HOST'] . '</code></p>');
    }

    /**
     * Allow access to configuration settings statically.
     *
     * @param mixed $key
     * @tutorial
     *    Config::get('some_value')
     */
    public static function get($key)
    {
        return self::$me->$key;
    }

    /**
     * Get singleton object instance
     *
     */
    public static function getInstance()
    {
        if (is_null(self::$me)) {
            self::$me = new Config();
        }
        return self::$me;
    }

    /**
     * Set authentication variables
     *
     */
    private function everywhere()
    {
        // Settings for the Authentication class
        $this->authDomain = $_SERVER['HTTP_HOST'];
        $this->authSalt = '';
    }

    /**
     * Get production default settings
     *
     */
    private function production()
    {
        ini_set('display_errors', 0);
        ini_set('error_reporting', E_ALL);

        $this->dbOnError = "die";
        $this->dbEmailOnError = false;
    }

    private function staging()
    {
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);

        $this->dbOnError = "die";
        $this->dbEmailOnError = false;
    }

    /**
     * Get local default settings
     *
     */
    private function local()
    {
        ini_set('display_errors', 1);
        ini_set('error_reporting', E_ALL);

        $this->dbOnError = "die";
        $this->dbEmailOnError = false;
    }

    /**
     * Get where i am located
     *
     */
    public function whereAmI()
    {
        for ($i = 0; $i < count($this->productionServers); $i++) {
            if (preg_match($this->productionServers[$i], $_SERVER['HTTP_HOST']) === 1) {
                return 'production';
            }
        }

        for ($i = 0; $i < count($this->stagingServers); $i++) {
            if (preg_match($this->stagingServers[$i], $_SERVER['HTTP_HOST']) === 1) {
                return 'staging';
            }
        }

        for ($i = 0; $i < count($this->localServers); $i++) {
            if (preg_match($this->localServers[$i], $_SERVER['HTTP_HOST']) === 1) {
                return 'local';
            }
        }

        return NULL;
    }

    /**
     * Notify user
     *
     * @param mixed $emailSubject
     * @param mixed $msg
     */
    public static function notify($emailSubject, $msg)
    {
        global $config;
        $email = $config['admin_email'];

        if (Config::getInstance()->dbEmailOnError == true) {
            $globals = print_r($GLOBALS, true);

            ob_start();
            debug_print_backtrace();
            $trace = ob_get_contents();
            ob_end_clean();

            $msg .= $trace . "\n\n";
            $msg .= $globals;

            mail($email, $emailSubject, $msg);
        }

        switch (Config::getInstance()->dbOnError) {
            case "die":
                echo $msg;
                echo "<pre>";
                debug_print_backtrace();
                echo "</pre>";
                break;

            case "redirect":
                Controller::redirect($config['error_controller']);
                break;

            default:
                break;
        }
    }
}
