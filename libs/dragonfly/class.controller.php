<?php

class Controller
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
    public static function json($data) {
        header("content-type:application/json");
        return json_encode($data, JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

}
