<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

class View
{
    private $pageVars = array();
    private $filename;
    private $tmpl;

    /**
     * Constructor of View
     */
    public function __construct($template)
    {
        $this->filename = $template;

        $this->tmpl = new Template();
        $this->tmpl->setPath('app/views/');
    }

    /**
     * Set variable to page view
     *
     * @param mixed $var
     * @param mixed $val
     */
    public function set($var, $val)
    {
        $this->pageVars[$var] = $val;
    }

    /**
     * Get variable in page view
     *
     * @param $var
     * @return null|mixed
     */
    public function get($var)
    {
        if (isset($this->pageVars[$var])) {
            return $this->pageVars[$var];
        }

        return null;
    }

    /**
     * Render page
     */
    public function render()
    {
        $this->tmpl->setVars($this->pageVars, true);
        $this->tmpl->display($this->filename . '.php');
    }
}
