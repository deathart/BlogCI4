<?php namespace CodeIgniter\Test;

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
 * @package	CodeIgniter
 * @author	CodeIgniter Dev Team
 * @copyright	2014-2018 British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 3.0.0
 * @filesource
 */
use Config\Paths;
use CodeIgniter\Services;
use CodeIgniter\Events\Events;
use PHPUnit\Framework\TestCase;
use Tests\Support\Log\TestLogger;

/**
 * PHPunit test case.
 */
class CIUnitTestCase extends TestCase
{
	use ReflectionHelper;

	/**
	 * @var \CodeIgniter\CodeIgniter
	 */
	protected $app;

	/**
	 * Path to Config folder, relative
	 * to the system folder.
	 * @var string
	 */
	protected $configPath = '../application/Config';

	public function setUp()
	{
		parent::setUp();

		if (! $this->app)
		{
			$this->app = $this->createApplication();
		}

		helper('url');
	}

	/**
	 * Custom function to hook into CodeIgniter's Logging mechanism
	 * to check if certain messages were logged during code execution.
	 *
	 * @param string $level
	 * @param null   $expectedMessage
	 *
	 * @throws \Exception
	 */
	public function assertLogged(string $level, $expectedMessage = null)
	{
		$result = TestLogger::didLog($level, $expectedMessage);

		$this->assertTrue($result);
		return $result;
	}

	/**
	 * Hooks into CodeIgniter's Events system to check if a specific
	 * event was triggered or not.
	 *
	 * @param string $eventName
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function assertEventTriggered(string $eventName): bool
	{
		$found = false;
		$eventName = strtolower($eventName);

		foreach (Events::getPerformanceLogs() as $log)
		{
			if ($log['event'] !== $eventName) continue;

			$found = true;
			break;
		}

		$this->assertTrue($found);
		return $found;
	}

	/**
	 * Loads up an instance of CodeIgniter
	 * and gets the environment setup.
	 *
	 * @return mixed
	 */
	protected function createApplication()
	{
		$systemPath = realpath(__DIR__.'/../');

		require_once $systemPath.'/'.$this->configPath.'/Paths.php';
		$paths = $this->adjustPaths(new \Config\Paths());

		$app = require $systemPath.'/bootstrap.php';
		return $app;
	}

	/**
	 * Attempts to adjust our system paths to account
	 * for relative location of our tests folder.
	 * Not foolproof, but works well for default locations.
	 *
	 * @param \Config\Paths $paths
	 *
	 * @return \Config\Paths
	 */
	protected function adjustPaths(Paths $paths)
	{
		$tests = [
			'systemDirectory', 'applicationDirectory', 'writableDirectory', 'testsDirectory'
		];

		foreach ($tests as $test)
		{
			if (is_dir($paths->$test) || strpos($paths->$test, '../') !== 0)
			{
				continue;
			}

			$check = substr($paths->$test, 3);
			if (is_dir($check))
			{
				$paths->$test = $check;
			}
		}

		return $paths;
	}
}
