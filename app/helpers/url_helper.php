<?php

class Url_helper {

	/**
	 * Get base_url from configuration
     *
	 */
	function base_url() {
		global $config;
		return $config['base_url'];
	}

    /**
     * Get segment of URL separated by /
     *
     * @param $seg
     * @return mixed
     */
	function segment($seg) {
		$parts = explode('/', trim($_GET['_url'], '/'));
		return $parts[$seg];
	}
	
}