<?php namespace CodeIgniter\Events;

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
define('EVENT_PRIORITY_LOW', 200);
define('EVENT_PRIORITY_NORMAL', 100);
define('EVENT_PRIORITY_HIGH', 10);

/**
 * Events
 */
class Events
{

	/**
	 * The list of listeners.
	 *
	 * @var array
	 */
	protected static $listeners = [];

	/**
	 * Flag to let us know if we've read from the Config file
	 * and have all of the defined events.
	 *
	 * @var bool
	 */
	protected static $haveReadFromFile = false;

	/**
	 * The path to the file containing the events to load in.
	 *
	 * @var string
	 */
	protected static $eventsFile;

	/**
	 * If true, events will not actually be fired.
	 * Useful during testing.
	 *
	 * @var bool
	 */
	protected static $simulate = false;

	/**
	 * Stores information about the events
	 * for display in the debug toolbar.
	 * @var array
	 */
	protected static $performanceLog = [];

	//--------------------------------------------------------------------

	/**
	 * Ensures that we have a events file ready.
	 *
	 * @param string|null $file
	 */
	public static function initialize(string $file = null)
	{
		// Don't overwrite anything....
		if ( ! empty(self::$eventsFile))
		{
			return;
		}

		// Default value
		if (empty($file))
		{
			$file = APPPATH . 'Config/Events.php';
		}

		self::$eventsFile = $file;
	}

	//--------------------------------------------------------------------

	/**
	 * Registers an action to happen on an event. The action can be any sort
	 * of callable:
	 *
	 *  Events::on('create', 'myFunction');               // procedural function
	 *  Events::on('create', ['myClass', 'myMethod']);    // Class::method
	 *  Events::on('create', [$myInstance, 'myMethod']);  // Method on an existing instance
	 *  Events::on('create', function() {});              // Closure
	 *
	 * @param          $event_name
	 * @param callable $callback
	 * @param int      $priority
	 */
	public static function on($event_name, callable $callback, $priority = EVENT_PRIORITY_NORMAL)
	{
		if ( ! isset(self::$listeners[$event_name]))
		{
			self::$listeners[$event_name] = [
				true, // If there's only 1 item, it's sorted.
				[$priority],
				[$callback],
			];
		}
		else
		{
			self::$listeners[$event_name][0] = false; // Not sorted
			self::$listeners[$event_name][1][] = $priority;
			self::$listeners[$event_name][2][] = $callback;
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Runs through all subscribed methods running them one at a time,
	 * until either:
	 *  a) All subscribers have finished or
	 *  b) a method returns false, at which point execution of subscribers stops.
	 *
	 * @param $eventName
	 * @param $arguments
	 *
	 * @return bool
	 */
	public static function trigger($eventName, ...$arguments): bool
	{
		// Read in our Config/events file so that we have them all!
		if ( ! self::$haveReadFromFile)
		{
			self::initialize();

			if (is_file(self::$eventsFile))
			{
				include self::$eventsFile;
			}
			self::$haveReadFromFile = true;
		}

		$listeners = self::listeners($eventName);

		foreach ($listeners as $listener)
		{
			$start = microtime(true);

			$result = static::$simulate === false
				? $listener(...$arguments)
				: true;

			if (CI_DEBUG)
			{
				static::$performanceLog[] = [
					'start' => $start,
					'end' => microtime(true),
					'event' => strtolower($eventName)
				];
			}

			if ($result === false)
			{
				return false;
			}
		}

		return true;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an array of listeners for a single event. They are
	 * sorted by priority.
	 *
	 * If the listener could not be found, returns FALSE, or TRUE if
	 * it was removed.
	 *
	 * @param $event_name
	 *
	 * @return array
	 */
	public static function listeners($event_name): array
	{
		if ( ! isset(self::$listeners[$event_name]))
		{
			return [];
		}

		// The list is not sorted
		if ( ! self::$listeners[$event_name][0])
		{
			// Sort it!
			array_multisort(self::$listeners[$event_name][1], SORT_NUMERIC, self::$listeners[$event_name][2]);

			// Mark it as sorted already!
			self::$listeners[$event_name][0] = true;
		}

		return self::$listeners[$event_name][2];
	}

	//--------------------------------------------------------------------

	/**
	 * Removes a single listener from an event.
	 *
	 * If the listener couldn't be found, returns FALSE, else TRUE if
	 * it was removed.
	 *
	 * @param          $event_name
	 * @param callable $listener
	 *
	 * @return bool
	 */
	public static function removeListener($event_name, callable $listener): bool
	{
		if ( ! isset(self::$listeners[$event_name]))
		{
			return false;
		}

		foreach (self::$listeners[$event_name][2] as $index => $check)
		{
			if ($check === $listener)
			{
				unset(self::$listeners[$event_name][1][$index]);
				unset(self::$listeners[$event_name][2][$index]);

				return true;
			}
		}

		return false;
	}

	//--------------------------------------------------------------------

	/**
	 * Removes all listeners.
	 *
	 * If the event_name is specified, only listeners for that event will be
	 * removed, otherwise all listeners for all events are removed.
	 *
	 * @param null $event_name
	 */
	public static function removeAllListeners($event_name = null)
	{
		if ( ! is_null($event_name))
		{
			unset(self::$listeners[$event_name]);
		}
		else
		{
			self::$listeners = [];
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Sets the path to the file that routes are read from.
	 *
	 * @param string $path
	 */
	public function setFile(string $path)
	{
		self::$eventsFile = $path;
	}

	//--------------------------------------------------------------------

	/**
	 * Turns simulation on or off. When on, events will not be triggered,
	 * simply logged. Useful during testing when you don't actually want
	 * the tests to run.
	 *
	 * @param bool $choice
	 */
	public static function simulate(bool $choice = true)
	{
		static::$simulate = $choice;
	}

	//--------------------------------------------------------------------

	/**
	 * Getter for the performance log records.
	 *
	 * @return array
	 */
	public static function getPerformanceLogs()
	{
		return static::$performanceLog;
	}

	//--------------------------------------------------------------------

}
