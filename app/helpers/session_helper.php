<?php

class Session_helper {

    /**
     * Set data to session state
     *
     * @param $key
     * @param $val
     */
	function set($key, $val) {
		$_SESSION[$key] = $val;
	}

    /**
     * Get data from session state
     *
     * @param $key
     * @return mixed
     */
	function get($key) {
		return $_SESSION[$key];
	}
	
	/**
	 * Destroy current session
     *
	 */
	function destroy() {
		session_destroy();
	}

}