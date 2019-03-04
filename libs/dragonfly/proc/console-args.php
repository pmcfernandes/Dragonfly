<?php
// Copyright (c) 2007, Yves Goergen, http://unclassified.de
// All rights reserved.
//
// Redistribution and use in source and binary forms, with or without modification, are permitted
// provided that the following conditions are met:
//
// * Redistributions of source code must retain the above copyright notice, this list of conditions
//   and the following disclaimer.
// * Redistributions in binary form must reproduce the above copyright notice, this list of
//   conditions and the following disclaimer in the documentation and/or other materials provided
//   with the distribution.
//
// THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR
// IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
// FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR
// CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
// CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
// SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
// THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
// OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
// POSSIBILITY OF SUCH DAMAGE.

// Command line arguments parser

// Known options definition: (where each "=" at the end denotes a following argument)
// $known_opts = array('a', 'b', 'c=');
// $known_longopts = array('aaa', 'bbb', 'ccc=');

$args = array();
$opts = array();

if (!isset($known_opts)) $known_opts = array();
if (!isset($known_longopts)) $known_longopts = array();

$process = true;
for ($i = 0; $i < $argc; $i++)
{
	$arg = $argv[$i];

	// This signals the end of any parameters
	if ($arg == '--')
	{
		$process = false;
	}

	// Check long options
	elseif ($process && substr($arg, 0, 2) == '--')
	{
		$ok = false;
		foreach ($known_longopts as $opt)
		{
			$add_args = 0;
			while (substr($opt, -1) == '=')
			{
				$add_args++;
				$opt = substr($opt, 0, -1);
			}

			if ($arg == '--' . $opt)
			{
				$this_opt = array($opt);
				while ($add_args--)
				{
					$i++;
					if (!isset($argv[$i])) die("Too few parameters for long option '$arg'\n");
					$this_opt[] = $argv[$i];
				}
				$opts[] = $this_opt;
				$ok = true;
				break;
			}
		}
		if (!$ok)
		{
			die("Unrecognised long option '$arg'\n");
		}
	}

	// Check short options
	// Don't touch a '-' on its own, leave it as normal parameter
	elseif ($process && strlen($arg) > 1 && substr($arg, 0, 1) == '-')
	{
		// For each character
		for ($c = 1; $c < strlen($arg); $c++)
		{
			$ch = $arg{$c};
			$ok = false;

			foreach ($known_opts as $opt)
			{
				$add_args = 0;
				while (substr($opt, -1) == '=')
				{
					$add_args++;
					$opt = substr($opt, 0, -1);
				}

				if ($ch == $opt)
				{
					$this_opt = array($opt);
					while ($add_args--)
					{
						$i++;
						if (!isset($argv[$i])) die("Too few parameters for option '-$ch'\n");
						$this_opt[] = $argv[$i];
					}
					$opts[] = $this_opt;
					$ok = true;
					break;
				}
			}
			if (!$ok)
			{
				die("Unrecognised option '-$ch'\n");
			}
		}
	}

	// Non-options
	else
	{
		$args[] = $arg;
	}
}

// Find if an option is set
// Optionally checks a second name and combines with OR
//
function is_option_set($name, $name2 = null)
{
	global $opts;

	foreach ($opts as $opt)
	{
		if ($opt[0] == $name)
		{
			return true;
		}
		if ($name2 && $opt[0] == $name2)
		{
			return true;
		}
	}
	return false;
}

// Get the first option parameter for a given option name
// Only finds the first occurance of an option
// Optionally checks a second name
//
function get_option_value($name, $name2 = null)
{
	global $opts;

	foreach ($opts as $opt)
	{
		if ($opt[0] == $name)
		{
			return $opt[1];
		}
		if ($name2 && $opt[0] == $name2)
		{
			return $opt[1];
		}
	}
}

// Get the first option parameter for a given option name
// Finds all occurances of an option
// Optionally checks a second name
//
function get_all_option_values($name, $name2 = null)
{
	global $opts;

	$ret = array();
	foreach ($opts as $opt)
	{
		if ($opt[0] == $name)
		{
			$ret[] = $opt[1];
		}
		if ($name2 && $opt[0] == $name2)
		{
			$ret[] = $opt[1];
		}
	}
	return $ret;
}

// Get all option parameters for a given option name
// Only finds the first occurance of an option
// Optionally checks a second name
//
function get_option_all_values($name, $name2 = null)
{
	global $opts;

	foreach ($opts as $opt)
	{
		if ($opt[0] == $name)
		{
			return array_slice($opt, 1);
		}
		if ($name2 && $opt[0] == $name2)
		{
			return array_slice($opt, 1);
		}
	}
}

// Get the n-th argument
//
function get_argument($n)
{
	global $args;

	if (isset($args[$n])) return $args[$n];
	return '';
}

?>