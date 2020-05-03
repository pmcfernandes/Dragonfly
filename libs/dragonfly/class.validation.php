<?php

class Validation
{

	/**
	 * Run an available validation test on a given value
	 *
	 * @access	public
	 * @param	string	value
	 * @param	string	method by which to test value
	 * @param	string	optional parameter for validation
	 * @return	boolean
	 */
	public function test($value, $method = 'required', $parameter = null)
	{
		$valid_methods = array('required', 'min_length', 'max_length', 'exact_length', 'alpha', 'alpha_numeric', 'alpha_dash', 'numeric');
		$requires_parameter = array('min_length', 'max_length', 'exact_length');

		if (!in_array($method, $valid_methods)) {
			return false;
		}

		$method = "valid_{$method}";

		if (in_array($method, $requires_parameter)) {
			return $this->$method($value, $parameter);
		} else {
			return $this->$method($value);
		}
	}

	/**
	 * Determine if the value is not empty
	 *
	 * @access	private
	 * @param	string	value to test
	 * @return	boolean
	 */
	private function valid_required($value)
	{
		if (strlen($value) > 0)
			return true;

		return false;
	}

	/**
	 * Determine if the value is of a minimum length
	 *
	 * @access	private
	 * @param	string	value to test
	 * @param	integer	minimum length
	 * @return	boolean
	 */
	private function valid_min_length($value, $min_length)
	{
		if (!is_int($min_length))
			return false;

		if (strlen($value) >= $min_length)
			return true;

		return false;
	}

	/**
	 * Determine if the value is of a maximum length
	 *
	 * @access	private
	 * @param	string	value to test
	 * @param	integer	maximum length
	 * @return	boolean
	 */
	private function valid_max_length($value, $max_length)
	{
		if (!is_int($max_length))
			return false;

		if (strlen($value) <= $max_length)
			return true;

		return false;
	}

	/**
	 * Determine if the value is of an exact length
	 *
	 * @access	private
	 * @param	string	value to test
	 * @param	integer	exact length
	 * @return	boolean
	 */
	private function valid_exact_length($value, $exact_length)
	{
		if (!is_int($exact_length))
			return false;

		if (strlen($value) == $exact_length)
			return true;

		return false;
	}

	/**
	 * Determine if the value is entirely alphabetic (a-z)
	 *
	 * @access	private
	 * @param	string	value to test
	 * @return	boolean
	 */
	private function valid_alpha($value)
	{
		if (preg_match("/([a-z]+)/i", $value))
			return true;

		return false;
	}

	/**
	 * Determine if the value is entirely numeric (0-9)
	 *
	 * @access	private
	 * @param	string	value to test
	 * @return	boolean
	 */
	private function valid_alpha_numeric($value)
	{
		if (preg_match("/([a-z0-9]+)/i", $value))
			return true;

		return false;
	}

	/**
	 * Determine if the value is composed of only alphabetic, numeric and dash '-' (a-z0-9-)
	 *
	 * @access	private
	 * @param	string	value to test
	 * @return	boolean
	 */
	private function valid_alpha_dash($value)
	{
		if (preg_match("/([-a-z0-9]+)/i", $value))
			return true;

		return false;
	}

	/**
	 * Determine if the value is entirely numeric (0-9)
	 *
	 * @access	private
	 * @param	string	value to test
	 * @return	boolean
	 */
	private function valid_numeric($value)
	{
		if (preg_match("/([0-9]+)/i", $value))
			return true;

		return false;
	}
}
