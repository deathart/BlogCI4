<?php namespace CodeIgniter\Cache\Handlers;

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
use CodeIgniter\Cache\CacheInterface;

class MemcachedHandler implements CacheInterface
{

	/**
	 * Prefixed to all cache names.
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * The memcached object
	 *
	 * @var \Memcached|\Memcache
	 */
	protected $memcached;

	/**
	 * Memcached Configuration
	 *
	 * @var array
	 */
	protected $config = [
		'host'	 => '127.0.0.1',
		'port'	 => 11211,
		'weight' => 1,
		'raw'	 => false,
	];

	//--------------------------------------------------------------------

	public function __construct(array $config)
	{
		$this->prefix = $config['prefix'] ?? '';

		if ( ! empty($config))
		{
			$this->config = array_merge($this->config, $config);
		}
	}

	/**
	 * Class destructor
	 *
	 * Closes the connection to Memcache(d) if present.
	 */
	public function __destruct()
	{
		if ($this->memcached instanceof \Memcached)
		{
			$this->memcached->quit();
		}
		elseif ($this->memcached instanceof \Memcache)
		{
			$this->memcached->close();
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Takes care of any handler-specific setup that must be done.
	 */
	public function initialize()
	{
		if (class_exists('\Memcached'))
		{
			$this->memcached = new \Memcached();
			if ($this->config['raw'])
			{
				$this->memcached->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
			}
		}
		elseif (class_exists('\Memcache'))
		{
			$this->memcached = new \Memcache();
		}
		else
		{
			throw new CriticalError('Cache: Not support Memcache(d) extension.');
		}

		if ($this->memcached instanceof \Memcached)
		{
			$this->memcached->addServer(
					$this->config['host'], $this->config['port'], $this->config['weight']
			);
		}
		elseif ($this->memcached instanceof \Memcache)
		{
			// Third parameter is persistance and defaults to TRUE.
			$this->memcached->addServer(
					$this->config['host'], $this->config['port'], true, $this->config['weight']
			);
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Attempts to fetch an item from the cache store.
	 *
	 * @param string $key Cache item name
	 *
	 * @return mixed
	 */
	public function get(string $key)
	{
		$key = $this->prefix . $key;

		$data = $this->memcached->get($key);

		return is_array($data) ? $data[0] : $data;
	}

	//--------------------------------------------------------------------

	/**
	 * Saves an item to the cache store.
	 *
	 * @param string $key   Cache item name
	 * @param mixed  $value The data to save
	 * @param int    $ttl   Time To Live, in seconds (default 60)
	 *
	 * @return mixed
	 */
	public function save(string $key, $value, int $ttl = 60)
	{
		$key = $this->prefix . $key;

		if ( ! $this->config['raw'])
		{
			$value = [$value, time(), $ttl];
		}

		if ($this->memcached instanceof \Memcached)
		{
			return $this->memcached->set($key, $value, $ttl);
		}
		elseif ($this->memcached instanceof \Memcache)
		{
			return $this->memcached->set($key, $value, 0, $ttl);
		}

		return false;
	}

	//--------------------------------------------------------------------

	/**
	 * Deletes a specific item from the cache store.
	 *
	 * @param string $key Cache item name
	 *
	 * @return mixed
	 */
	public function delete(string $key)
	{
		$key = $this->prefix . $key;

		return $this->memcached->delete($key);
	}

	//--------------------------------------------------------------------

	/**
	 * Performs atomic incrementation of a raw stored value.
	 *
	 * @param string $key    Cache ID
	 * @param int    $offset Step/value to increase by
	 *
	 * @return mixed
	 */
	public function increment(string $key, int $offset = 1)
	{
		if ( ! $this->config['raw'])
		{
			return false;
		}

		$key = $this->prefix . $key;

		return $this->memcached->increment($key, $offset, $offset, 60);
	}

	//--------------------------------------------------------------------

	/**
	 * Performs atomic decrementation of a raw stored value.
	 *
	 * @param string $key    Cache ID
	 * @param int    $offset Step/value to increase by
	 *
	 * @return mixed
	 */
	public function decrement(string $key, int $offset = 1)
	{
		if ( ! $this->config['raw'])
		{
			return false;
		}

		$key = $this->prefix . $key;

		//FIXME: third parameter isn't other handler actions.
		return $this->memcached->decrement($key, $offset, $offset, 60);
	}

	//--------------------------------------------------------------------

	/**
	 * Will delete all items in the entire cache.
	 *
	 * @return mixed
	 */
	public function clean()
	{
		return $this->memcached->flush();
	}

	//--------------------------------------------------------------------

	/**
	 * Returns information on the entire cache.
	 *
	 * The information returned and the structure of the data
	 * varies depending on the handler.
	 *
	 * @return mixed
	 */
	public function getCacheInfo()
	{
		return $this->memcached->getStats();
	}

	//--------------------------------------------------------------------

	/**
	 * Returns detailed information about the specific item in the cache.
	 *
	 * @param string $key Cache item name.
	 *
	 * @return mixed
	 */
	public function getMetaData(string $key)
	{
		$key = $this->prefix . $key;

		$stored = $this->memcached->get($key);

		if (count($stored) !== 3)
		{
			return FALSE;
		}

		list($data, $time, $ttl) = $stored;

		return [
			'expire' => $time + $ttl,
			'mtime'	 => $time,
			'data'	 => $data
		];
	}

	//--------------------------------------------------------------------

	/**
	 * Determines if the driver is supported on this system.
	 *
	 * @return boolean
	 */
	public function isSupported(): bool
	{
		return (extension_loaded('memcached') OR extension_loaded('memcache'));
	}

}
