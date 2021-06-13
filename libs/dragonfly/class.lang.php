<?php
defined('DRAGONFLY_LIB_PATH') or die('No direct script access allowed');

class Lang
{
	public $phrases = array();

	function __construct()
	{
		$l = get_cookie('lang');
		$lang = (!empty($l) ? $l : constant('DEFAULT_LANGUAGE'));

		if (file_exists("languages/$lang.ini")) {
			$this->phrases = parse_ini_file("languages/$lang.ini");
		}
	}

	/**
	 * Get a language phrase with a key
	 * @return string
	 */
	public function getPhrase($key)
	{
		$phrase = isset($this->phrases[$key]) ? $this->phrases[$key] : null;
		return $phrase;
	}
}
