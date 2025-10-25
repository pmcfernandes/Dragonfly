<?php

use Impedro\Dragonfly\Mvc\Controller;

class ErrorController extends Controller {

    /**
     * Default Action for Error Controller
     */
	function index() {
		$this->error404();
	}

    /**
     * 404 Error Action
     *
     */
	function error404() {
		echo '<h1>404 Error</h1>';
		echo '<p>Looks like this page don\'t exist</p>';
	}

}
