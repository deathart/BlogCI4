<?php namespace CodeIgniter\Config;

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014-2018 British Columbia Institute of Technology
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
 * @package      CodeIgniter
 * @author       CodeIgniter Dev Team
 * @copyright    2014-2018 British Columbia Institute of Technology (https://bcit.ca/)
 * @license      https://opensource.org/licenses/MIT	MIT License
 * @link         https://codeigniter.com
 * @since        Version 3.0.0
 * @filesource
 */

use CodeIgniter\Autoloader\FileLocator;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This is used in place of a Dependency Injection container primarily
 * due to its simplicity, which allows a better long-term maintenance
 * of the applications built on top of CodeIgniter. A bonus side-effect
 * is that IDEs are able to determine what class you are calling
 * whereas with DI Containers there usually isn't a way for them to do this.
 *
 * @see http://blog.ircmaxell.com/2015/11/simple-easy-risk-and-change.html
 * @see http://www.infoq.com/presentations/Simple-Made-Easy
 */
class BaseService
{

	/**
	 * Cache for instance of any services that
	 * have been requested as a "shared" instance.
	 *
	 * @var array
	 */
	static protected $instances = [];

	/**
	 * Mock objects for testing which are returned if exist.
	 *
	 * @var array
	 */
	static protected $mocks = [];

	/**
	 * Have we already discovered other Services?
	 *
	 * @var bool
	 */
	static protected $discovered = false;

	/**
	 * A cache of other service classes we've found.
	 *
	 * @var array
	 */
	static protected $services = [];

	//--------------------------------------------------------------------

	/**
	 * Returns a shared instance of any of the class' services.
	 *
	 * $key must be a name matching a service.
	 *
	 * @param string $key
	 * @param array  ...$params
	 *
	 * @return mixed
	 */
	protected static function getSharedInstance(string $key, ...$params)
	{
		// Returns mock if exists
		if (isset(static::$mocks[$key]))
		{
			return static::$mocks[$key];
		}

		if (! isset(static::$instances[$key]))
		{
			// Make sure $getShared is false
			array_push($params, false);

			static::$instances[$key] = static::$key(...$params);
		}

		return static::$instances[$key];
	}

	//--------------------------------------------------------------------

	/**
	 * Provides the ability to perform case-insensitive calling of service
	 * names.
	 *
	 * @param string $name
	 * @param array  $arguments
	 *
	 * @return mixed
	 */
	public static function __callStatic(string $name, array $arguments)
	{
		$name = strtolower($name);

		if (method_exists(__CLASS__, $name))
		{
			return Services::$name(...$arguments);
		}

		return static::discoverServices($name, $arguments);
	}

	//--------------------------------------------------------------------

	/**
	 * Reset shared instances and mocks for testing.
	 */
	public static function reset()
	{
		static::$mocks = [];

		static::$instances = [];
	}

	//--------------------------------------------------------------------

	/**
	 * Inject mock object for testing.
	 *
	 * @param string $name
	 * @param        $mock
	 */
	public static function injectMock(string $name, $mock)
	{
		$name                 = strtolower($name);
		static::$mocks[$name] = $mock;
	}

	//--------------------------------------------------------------------

	/**
	 * Will scan all psr4 namespaces registered with system to look
	 * for new Config\Services files. Caches a copy of each one, then
	 * looks for the service method in each, returning an instance of
	 * the service, if available.
	 *
	 * @param string $name
	 * @param array  $arguments
	 */
	protected static function discoverServices(string $name, array $arguments)
	{
		if (! static::$discovered)
		{
			$locator = static::locator();
			$files   = $locator->search('Config/Services');

			if (empty($files))
			{
				return;
			}

			// Get instances of all service classes and cache them locally.
			foreach ($files as $file)
			{
				$classname = $locator->getClassname($file);

				if (! in_array($classname, ['Config\\Services', 'CodeIgniter\\Config\\Services']))
				{
					static::$services[] = new $classname();
				}
			}
		}

		if (! count(static::$services))
		{
			return;
		}

		// Try to find the desired service method
		foreach (static::$services as $class)
		{
			if (method_exists($class, $name))
			{
				return $class::$name(...$arguments);
			}
		}
	}
}
