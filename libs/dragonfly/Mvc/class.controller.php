<?php
namespace Impedro\Dragonfly\Mvc;

defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

use Impedro\Dragonfly\Mvc\AbstractController;
use Impedro\Dragonfly\Mvc\View;

class Controller extends AbstractController
{
    /**
     * Load Model
     *
     * @param mixed $name
     */
    public function loadModel($name)  {
        require_once('app/models/' . strtolower($name) . '.php');
        $model = new $name;
        return $model;
    }

    /**
     * Load View
     *
     * @param $name
     * @return View
     */
    public function loadView($name) {
        $view = new View($name);
        return $view;
    }

    /**
     * Load Plugin
     *
     * @param mixed $name
     */
    public function loadPlugin($name) {
        require_once('app/plugins/' . strtolower($name) . '.php');
    }

    /**
     * Load Helper
     *
     * @param mixed $name
     */
    public function loadHelper($name)
    {
        require_once('app/helpers/' . strtolower($name) . '.php');
        $helper = new $name;
        return $helper;
    }

    /**
     * Redirect view to other view
     *
     * @param mixed $location
     */
    public static function redirect($location)  {
        global $config;
        header('Location: ' . $config['base_url'] . $location);
    }

    /**
     * Return json encoded data
     *
     * @param mixed $data
     * @return void
     */
    public function json($data) : string {
        header("content-type:application/json");
        return json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    /**
     * Return custom json data
     *
     * @param [type] $data
     * @param [type] $error
     * @return void
     */
    public function ajs($data, $error = null) : string {
        header("content-type:application/json");

        return json_encode([
            'success'             => ($error === null),
            'targetUrl'           => '/',
            'error'               => $error,
            'unAuthorizedRequest' => false,
            '__ajs'               => $data
        ], JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    /**
     * 400 Bad request
     *
     * @return void
     */
    public function bad_request() {
        header('HTTP/1.0 400 Bad Request');
        exit();
    }

    /**
     * 401 Unauhorized
     *
     * @return void
     */
    public function unauthorized() {
        header('HTTP/1.1 401 Unauthorized');
        exit();
    }

    /**
     * 200 OK
     *
     * @return void
     */
    public function ok() {
        header('HTTP/1.1 200 OK');
        exit();
    }

    /**
     * 500
     *
     * @param string $message
     * @return void
     */
    public function panic($message = '') {
        header('HTTP/1.1 500 ' .  $message);
        exit();
    }
}
