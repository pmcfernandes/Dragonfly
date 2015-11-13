<?php

class HomeController extends Controller
{

    /**
     * Default Action for Home Controller
     */
    function index() {
        $template = $this->loadView("home/index");
        $template->set('name', 'Jonh Doe');
        $template->render();
    }
}