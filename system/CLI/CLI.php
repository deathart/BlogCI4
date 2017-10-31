<?php namespace CodeIgniter\CLI;

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014-2017 British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	CodeIgniter Dev Team
 * @copyright	2014-2017 British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 3.0.0
 * @filesource
 */

/**
 * Class CLI
 *
 * Tools to interact with that request since CLI requests are not
 * static like HTTP requests might be.
 *
 * Portions of this code were initially from the FuelPHP Framework,
 * version 1.7.x, and used here under the MIT license they were
 * originally made available under.
 *
 * http://fuelphp.com
 *
 * @package CodeIgniter\HTTP
 */
class CLI
{

	/**
	 * Is the readline library on the system?
	 *
	 * @var bool
	 */
	public static $readline_support = false;

	/**
	 * The message displayed at prompts.
	 *
	 * @var string
	 */
	public static $wait_msg = 'Press any key to continue...';

	/**
	 * Has the class already been initialized?
	 *
	 * @var bool
	 */
	protected static $initialized = false;

	/**
	 * Foreground color list
	 * @var array
	 */
	protected static $foreground_colors = [
		'black'			 => '0;30',
		'dark_gray'		 => '1;30',
		'blue'			 => '0;34',
		'dark_blue'		 => '1;34',
		'light_blue'	 => '1;34',
		'green'			 => '0;32',
		'light_green'	 => '1;32',
		'cyan'			 => '0;36',
		'light_cyan'	 => '1;36',
		'red'			 => '0;31',
		'light_red'		 => '1;31',
		'purple'		 => '0;35',
		'light_purple'	 => '1;35',
		'light_yellow'	 => '0;33',
		'yellow'		 => '1;33',
		'light_gray'	 => '0;37',
		'white'			 => '1;37',
	];

	/**
	 * Background color list
	 * @var array
	 */
	protected static $background_colors = [
		'black'		 => '40',
		'red'		 => '41',
		'green'		 => '42',
		'yellow'	 => '43',
		'blue'		 => '44',
		'magenta'	 => '45',
		'cyan'		 => '46',
		'light_gray' => '47',
	];

	/**
	 * List of array segments.
	 * @var array
	 */
	protected static $segments = [];
	/**
	 * @var array
	 */
	protected static $options = [];

	//--------------------------------------------------------------------

	/**
	 * Static "constructor".
	 */
	public static function init()
	{
		// Readline is an extension for PHP that makes interactivity with PHP
		// much more bash-like.
		// http://www.php.net/manual/en/readline.installation.php
		static::$readline_support = extension_loaded('readline');

		static::parseCommandLine();

		static::$initialized = true;
	}

	//--------------------------------------------------------------------

	/**
	 * Get input from the shell, using readline or the standard STDIN
	 *
	 * Named options must be in the following formats:
	 * php index.php user -v --v -name=John --name=John
	 *
	 * @param    string $prefix
	 *
	 * @return    string
	 */
	public static function input(string $prefix = null): string
	{
		if (static::$readline_support)
		{
			return readline($prefix);
		}

		echo $prefix;

		return fgets(STDIN);
	}

	//--------------------------------------------------------------------

	/**
	 * Asks the user for input.  This can have either 1 or 2 arguments.
	 *
	 * Usage:
	 *
	 * // Waits for any key press
	 * CLI::prompt();
	 *
	 * // Takes any input
	 * $color = CLI::prompt('What is your favorite color?');
	 *
	 * // Takes any input, but offers default
	 * $color = CLI::prompt('What is your favourite color?', 'white');
	 *
	 * // Will only accept the options in the array
	 * $ready = CLI::prompt('Are you ready?', array('y','n'));
	 *
	 * @return    string    The user input
	 */
	public static function prompt(): string
	{
		$args = func_get_args();

		$options = [];
		$output = '';
		$default = null;

		// How many we got
		$arg_count = count($args);

		// Is the last argument a boolean? True means required
		$required = end($args) === true;

		// Reduce the argument count if required was passed, we don't care about that anymore
		$required === true && -- $arg_count;

		// This method can take a few crazy combinations of arguments, so lets work it out
		switch ($arg_count)
		{
			case 2:

				// E.g: $ready = CLI::prompt('Are you ready?', array('y','n'));
				if (is_array($args[1]))
				{
					list($output, $options) = $args;
				}

				// E.g: $color = CLI::prompt('What is your favourite color?', 'white');
				elseif (is_string($args[1]))
				{
					list($output, $default) = $args;
				}

				break;

			case 1:

				// No question (probably been asked already) so just show options
				// E.g: $ready = CLI::prompt(array('y','n'));
				if (is_array($args[0]))
				{
					$options = $args[0];
				}

				// Question without options
				// E.g: $ready = CLI::prompt('What did you do today?');
				elseif (is_string($args[0]))
				{
					$output = $args[0];
				}

				break;
		}

		// If a question has been asked with the read
		if ($output !== '')
		{
			$extra_output = '';

			if ($default !== null)
			{
				$extra_output = ' [ Default: "' . $default . '" ]';
			}
			elseif (! empty($options))
			{
				$extra_output = ' [ ' . implode(', ', $options) . ' ]';
			}

			fwrite(STDOUT, $output . $extra_output . ': ');
		}

		// Read the input from keyboard.
		$input = trim(static::input()) ?: $default;

		// No input provided and we require one (default will stop this being called)
		if (empty($input) && $required === true)
		{
			static::write('This is required.');
			static::newLine();

			$input = forward_static_call_array([__CLASS__, 'prompt'], $args);
		}

		// If options are provided and the choice is not in the array, tell them to try again
		if ( ! empty($options) && ! in_array($input, $options))
		{
			static::write('This is not a valid option. Please try again.');
			static::newLine();

			$input = forward_static_call_array([__CLASS__, 'prompt'], $args);
		}

		return empty($input) ? '' : $input;
	}

	//--------------------------------------------------------------------

	/**
	 * Outputs a string to the cli.
	 *
	 * @param string $text          The text to output
	 * @param string $foreground
	 * @param string $background
	 */
	public static function write(string $text = '', string $foreground = null, string $background = null)
	{
		if ($foreground || $background)
		{
			$text = static::color($text, $foreground, $background);
		}

		fwrite(STDOUT, $text . PHP_EOL);
	}

	//--------------------------------------------------------------------

	/**
	 * Outputs an error to the CLI using STDERR instead of STDOUT
	 *
	 * @param    string|array $text The text to output, or array of errors
	 * @param string          $foreground
	 * @param string          $background
	 */
	public static function error(string $text, string $foreground = 'light_red', string $background = null)
	{
		if ($foreground || $background)
		{
			$text = static::color($text, $foreground, $background);
		}

		fwrite(STDERR, $text . PHP_EOL);
	}

	//--------------------------------------------------------------------

	/**
	 * Beeps a certain number of times.
	 *
	 * @param    int $num The number of times to beep
	 */
	public static function beep(int $num = 1)
	{
		echo str_repeat("\x07", $num);
	}

	//--------------------------------------------------------------------

	/**
	 * Waits a certain number of seconds, optionally showing a wait message and
	 * waiting for a key press.
	 *
	 * @param    int  $seconds   Number of seconds
	 * @param    bool $countdown Show a countdown or not
	 */
	public static function wait(int $seconds, bool $countdown = false)
	{
		if ($countdown === true)
		{
			$time = $seconds;

			while ($time > 0)
			{
				fwrite(STDOUT, $time . '... ');
				sleep(1);
				$time --;
			}
			static::write();
		}
		else
		{
			if ($seconds > 0)
			{
				sleep($seconds);
			}
			else
			{
				static::write(static::$wait_msg);
				static::input();
			}
		}
	}

	//--------------------------------------------------------------------

	/**
	 * if operating system === windows
	 */
	public static function isWindows()
	{
		return 'win' === strtolower(substr(php_uname("s"), 0, 3));
	}

	//--------------------------------------------------------------------

	/**
	 * Enter a number of empty lines
	 *
	 * @param    int $num   Number of lines to output
	 *
	 * @return    void
	 */
	public static function newLine(int $num = 1)
	{
		// Do it once or more, write with empty string gives us a new line
		for ($i = 0; $i < $num; $i ++ )
		{
			static::write('');
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Clears the screen of output
	 *
	 * @return    void
	 */
	public static function clearScreen()
	{
		static::isWindows()

				// Windows is a bit crap at this, but their terminal is tiny so shove this in
						? static::newLine(40)

				// Anything with a flair of Unix will handle these magic characters
						: fwrite(STDOUT, chr(27) . "[H" . chr(27) . "[2J");
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the given text with the correct color codes for a foreground and
	 * optionally a background color.
	 *
	 * @param    string $text       The text to color
	 * @param    string $foreground The foreground color
	 * @param    string $background The background color
	 * @param    string $format     Other formatting to apply. Currently only 'underline' is understood
	 *
	 * @return    string    The color coded string
	 */
	public static function color(string $text, string $foreground, string $background = null, string $format = null)
	{
		if (static::isWindows() && ! isset($_SERVER['ANSICON']))
		{
			return $text;
		}

		if ( ! array_key_exists($foreground, static::$foreground_colors))
		{
			throw new \RuntimeException('Invalid CLI foreground color: ' . $foreground);
		}

		if ($background !== null && ! array_key_exists($background, static::$background_colors))
		{
			throw new \RuntimeException('Invalid CLI background color: ' . $background);
		}

		$string = "\033[" . static::$foreground_colors[$foreground] . "m";

		if ($background !== null)
		{
			$string .= "\033[" . static::$background_colors[$background] . "m";
		}

		if ($format === 'underline')
		{
			$string .= "\033[4m";
		}

		$string .= $text . "\033[0m";

		return $string;
	}

	//--------------------------------------------------------------------

	/**
	 * Attempts to determine the width of the viewable CLI window.
	 * This only works on *nix-based systems, so return a sane default
	 * for Windows environments.
	 *
	 * @param int $default
	 *
	 * @return int
	 */
	public static function getWidth(int $default = 80): int
	{
		if (static::isWindows())
		{
			return $default;
		}

		return (int) shell_exec('tput cols');
	}

	//--------------------------------------------------------------------

	/**
	 * Attempts to determine the height of the viewable CLI window.
	 * This only works on *nix-based systems, so return a sane default
	 * for Windows environments.
	 *
	 * @param int $default
	 *
	 * @return int
	 */
	public static function getHeight(int $default = 32): int
	{
		if (static::isWindows())
		{
			return $default;
		}

		return (int) shell_exec('tput lines');
	}

	//--------------------------------------------------------------------

	/**
	 * Displays a progress bar on the CLI. You must call it repeatedly
	 * to update it. Set $thisStep = false to erase the progress bar.
	 *
	 * @param int $thisStep
	 * @param int $totalSteps
	 */
	public static function showProgress($thisStep = 1, int $totalSteps = 10)
	{
		static $inProgress = false;

		// restore cursor position when progress is continuing.
		if ($inProgress !== false && $inProgress <= $thisStep)
		{
			fwrite(STDOUT, "\033[1A");
		}
		$inProgress = $thisStep;

		if ($thisStep !== false)
		{
			// Don't allow div by zero or negative numbers....
			$thisStep = abs($thisStep);
			$totalSteps = $totalSteps < 1 ? 1 : $totalSteps;

			$percent = intval(($thisStep / $totalSteps) * 100);
			$step = (int) round($percent / 10);

			// Write the progress bar
			fwrite(STDOUT, "[\033[32m" . str_repeat('#', $step) . str_repeat('.', 10 - $step) . "\033[0m]");
			// Textual representation...
			fwrite(STDOUT, sprintf(" %3d%% Complete", $percent) . PHP_EOL);
		}
		else
		{
			fwrite(STDOUT, "\007");
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Takes a string and writes it to the command line, wrapping to a maximum
	 * width. If no maximum width is specified, will wrap to the window's max
	 * width.
	 *
	 * If an int is passed into $pad_left, then all strings after the first
	 * will padded with that many spaces to the left. Useful when printing
	 * short descriptions that need to start on an existing line.
	 *
	 * @param string $string
	 * @param int  $max
	 * @param int  $pad_left
	 *
	 * @return string
	 */
	public static function wrap(string $string = null, int $max = 0, int $pad_left = 0): string
	{
		if (empty($string))
		{
			return '';
		}

		if ($max == 0)
		{
			$max = CLI::getWidth();
		}

		if (CLI::getWidth() < $max)
		{
			$max = CLI::getWidth();
		}

		$max = $max - $pad_left;

		$lines = wordwrap($string, $max);

		if ($pad_left > 0)
		{
			$lines = explode(PHP_EOL, $lines);

			$first = true;

			array_walk($lines, function (&$line, $index) use ($pad_left, &$first) {
				if ( ! $first)
				{
					$line = str_repeat(" ", $pad_left) . $line;
				}
				else
				{
					$first = false;
				}
			});

			$lines = implode(PHP_EOL, $lines);
		}

		return $lines;
	}

	//--------------------------------------------------------------------
	//--------------------------------------------------------------------
	// Command-Line 'URI' support
	//--------------------------------------------------------------------

	/**
	 * Parses the command line it was called from and collects all
	 * options and valid segments.
	 *
	 * I tried to use getopt but had it fail occassionally to find any
	 * options but argc has always had our back. We don't have all of the power
	 * of getopt but this does us just fine.
	 */
	protected static function parseCommandLine()
	{
		$optionsFound = false;

		for ($i = 1; $i < $_SERVER['argc']; $i ++ )
		{
			// If there's no '-' at the beginning of the argument
			// then add it to our segments.
			if ( ! $optionsFound && mb_strpos($_SERVER['argv'][$i], '-') === false)
			{
				static::$segments[] = $_SERVER['argv'][$i];
				continue;
			}

			// We set $optionsFound here so that we know to
			// skip the next argument since it's likely the
			// value belonging to this option.
			$optionsFound = true;

			if (mb_substr($_SERVER['argv'][$i], 0, 1) != '-')
			{
				continue;
			}

			$arg = str_replace('-', '', $_SERVER['argv'][$i]);
			$value = null;

			// if the next item doesn't have a dash it's a value.
			if (isset($_SERVER['argv'][$i + 1]) && mb_substr($_SERVER['argv'][$i + 1], 0, 1) != '-')
			{
				$value = $_SERVER['argv'][$i + 1];
				$i ++;
			}

			static::$options[$arg] = $value;

			// Reset $optionsFound so it can collect segments
			// past any options.
			$optionsFound = false;
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the command line string portions of the arguments, minus
	 * any options, as a string. This is used to pass along to the main
	 * CodeIgniter application.
	 *
	 * @return string
	 */
	public static function getURI()
	{
		return implode('/', static::$segments);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an individual segment.
	 *
	 * This ignores any options that might have been dispersed between
	 * valid segments in the command:
	 *
	 *  // segment(3) is 'three', not '-f' or 'anOption'
	 *  > php spark one two -f anOption three
	 *
	 * @param int $index
	 *
	 * @return mixed|null
	 */
	public static function getSegment(int $index)
	{
		if ( ! isset(static::$segments[$index - 1]))
		{
			return null;
		}

		return static::$segments[$index - 1];
	}

	//--------------------------------------------------------------------

	/**
	 * Gets a single command-line option. Returns TRUE if the option
	 * exists, but doesn't have a value, and is simply acting as a flag.
	 *
	 * @param string $name
	 *
	 * @return bool|mixed|null
	 */
	public static function getOption(string $name)
	{
		if ( ! array_key_exists($name, static::$options))
		{
			return null;
		}

		// If the option didn't have a value, simply return TRUE
		// so they know it was set, otherwise return the actual value.
		$val = static::$options[$name] === null ? true : static::$options[$name];

		return $val;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the raw array of options found.
	 *
	 * @return array
	 */
	public static function getOptions()
	{
		return static::$options;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the options a string, suitable for passing along on
	 * the CLI to other commands.
	 *
	 * @return string
	 */
	public static function getOptionString(): string
	{
		if (empty(static::$options))
		{
			return '';
		}

		$out = '';

		foreach (static::$options as $name => $value)
		{
			// If there's a space, we need to group
			// so it will pass correctly.
			if (mb_strpos($value, ' ') !== false)
			{
				$value = '"' . $value . '"';
			}

			$out .= "-{$name} $value ";
		}

		return $out;
	}

	//--------------------------------------------------------------------
}

// Ensure the class is initialized.
CLI::init();
