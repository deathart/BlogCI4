<?php namespace CodeIgniter\HTTP;

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
 * Representation of an iHTTP request.
 *
 */
class Request extends Message implements RequestInterface
{

	/**
	 * IP address of the current user.
	 *
	 * @var string
	 */
	protected $ipAddress = '';

	/**
	 * Proxy IPs
	 *
	 * @var string|array
	 */
	protected $proxyIPs;

	/**
	 * Request method.
	 *
	 * @var string
	 */
	protected $method;

	//--------------------------------------------------------------------

	/**
	 * Constructor.
	 *
	 * @param object $config
	 */
	public function __construct($config)
	{
		$this->proxyIPs = $config->proxyIPs;

		$this->method = $this->getServer('REQUEST_METHOD') ?? 'GET';
	}

	//--------------------------------------------------------------------

	/**
	 * Gets the user's IP address.
	 *
	 * @return string IP address
	 */
	public function getIPAddress(): string
	{
		if ( ! empty($this->ipAddress))
		{
			return $this->ipAddress;
		}

		$proxy_ips = $this->proxyIPs;
		if ( ! empty($this->proxyIPs) && ! is_array($this->proxyIPs))
		{
			$proxy_ips = explode(',', str_replace(' ', '', $this->proxyIPs));
		}

		$this->ipAddress = $this->getServer('REMOTE_ADDR');

		if ($proxy_ips)
		{
			foreach (['HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_X_CLIENT_IP', 'HTTP_X_CLUSTER_CLIENT_IP'] as $header)
			{
				if (($spoof = $this->getServer($header)) !== NULL)
				{
					// Some proxies typically list the whole chain of IP
					// addresses through which the client has reached us.
					// e.g. client_ip, proxy_ip1, proxy_ip2, etc.
					sscanf($spoof, '%[^,]', $spoof);

					if ( ! $this->isValidIP($spoof))
					{
						$spoof = NULL;
					}
					else
					{
						break;
					}
				}
			}

			if ($spoof)
			{
				for ($i = 0, $c = count($this->proxyIPs); $i < $c; $i ++ )
				{
					// Check if we have an IP address or a subnet
					if (strpos($proxy_ips[$i], '/') === FALSE)
					{
						// An IP address (and not a subnet) is specified.
						// We can compare right away.
						if ($proxy_ips[$i] === $this->ipAddress)
						{
							$this->ipAddress = $spoof;
							break;
						}

						continue;
					}

					// We have a subnet ... now the heavy lifting begins
					isset($separator) OR $separator = $this->isValidIP($this->ipAddress, 'ipv6') ? ':' : '.';

					// If the proxy entry doesn't match the IP protocol - skip it
					if (strpos($proxy_ips[$i], $separator) === FALSE)
					{
						continue;
					}

					// Convert the REMOTE_ADDR IP address to binary, if needed
					if ( ! isset($ip, $sprintf))
					{
						if ($separator === ':')
						{
							// Make sure we're have the "full" IPv6 format
							$ip = explode(':', str_replace('::', str_repeat(':', 9 - substr_count($this->ipAddress, ':')), $this->ipAddress
									)
							);

							for ($j = 0; $j < 8; $j ++ )
							{
								$ip[$j] = intval($ip[$j], 16);
							}

							$sprintf = '%016b%016b%016b%016b%016b%016b%016b%016b';
						}
						else
						{
							$ip = explode('.', $this->ipAddress);
							$sprintf = '%08b%08b%08b%08b';
						}

						$ip = vsprintf($sprintf, $ip);
					}

					// Split the netmask length off the network address
					sscanf($proxy_ips[$i], '%[^/]/%d', $netaddr, $masklen);

					// Again, an IPv6 address is most likely in a compressed form
					if ($separator === ':')
					{
						$netaddr = explode(':', str_replace('::', str_repeat(':', 9 - substr_count($netaddr, ':')), $netaddr));
						for ($i = 0; $i < 8; $i ++ )
						{
							$netaddr[$i] = intval($netaddr[$i], 16);
						}
					}
					else
					{
						$netaddr = explode('.', $netaddr);
					}

					// Convert to binary and finally compare
					if (strncmp($ip, vsprintf($sprintf, $netaddr), $masklen) === 0)
					{
						$this->ipAddress = $spoof;
						break;
					}
				}
			}
		}

		if ( ! $this->isValidIP($this->ipAddress))
		{
			return $this->ipAddress = '0.0.0.0';
		}

		return empty($this->ipAddress) ? '' : $this->ipAddress;
	}

	//--------------------------------------------------------------------

	/**
	 * Validate an IP address
	 *
	 * @param string $ip     IP Address
	 * @param string $which  IP protocol: 'ipv4' or 'ipv6'
	 *
	 * @return bool
	 */
	public function isValidIP(string $ip, string $which = null): bool
	{
		switch (strtolower($which))
		{
			case 'ipv4':
				$which = FILTER_FLAG_IPV4;
				break;
			case 'ipv6':
				$which = FILTER_FLAG_IPV6;
				break;
			default:
				$which = NULL;
				break;
		}

		return (bool) filter_var($ip, FILTER_VALIDATE_IP, $which);
	}

	//--------------------------------------------------------------------

	/**
	 * Get the request method.
	 *
	 * @param bool $upper Whether to return in upper or lower case.
	 *
	 * @return string
	 */
	public function getMethod($upper = false): string
	{
		return ($upper) ? strtoupper($this->method) : strtolower($this->method);
	}

	//--------------------------------------------------------------------

	/**
	 * Sets the request method. Used when spoofing the request.
	 *
	 * @param string $method
	 *
	 * @return Request
	 */
	public function setMethod(string $method)
	{
		$this->method = $method;

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch an item from the $_SERVER array.
	 *
	 * @param int|null $index  Index for item to be fetched from $_SERVER
	 * @param int|null $filter A filter name to be applied
	 * @param null     $flags
	 *
	 * @return mixed
	 */
	public function getServer($index = null, $filter = null, $flags = null)
	{
		return $this->fetchGlobal(INPUT_SERVER, $index, $filter, $flags);
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch an item from the $_ENV array.
	 *
	 * @param null $index  Index for item to be fetched from $_ENV
	 * @param null $filter A filter name to be applied
	 * @param null $flags
	 *
	 * @return mixed
	 */
	public function getEnv($index = null, $filter = null, $flags = null)
	{
		return $this->fetchGlobal(INPUT_ENV, $index, $filter, $flags);
	}

	//--------------------------------------------------------------------

	/**
	 * Fetches one or more items from a global, like cookies, get, post, etc.
	 * Can optionally filter the input when you retrieve it by passing in
	 * a filter.
	 *
	 * If $type is an array, it must conform to the input allowed by the
	 * filter_input_array method.
	 *
	 * http://php.net/manual/en/filter.filters.sanitize.php
	 *
	 * @param int          $type   Input filter constant
	 * @param string|array $index
	 * @param int          $filter Filter constant
	 * @param null         $flags
	 *
	 * @return mixed
	 */
	protected function fetchGlobal($type, $index = null, $filter = null, $flags = null )
	{
		// Null filters cause null values to return.
		if (is_null($filter))
		{
			$filter = FILTER_DEFAULT;
		}

		$loopThrough = [];
		switch ($type)
		{
			case INPUT_GET : $loopThrough = $_GET;
				break;
			case INPUT_POST : $loopThrough = $_POST;
				break;
			case INPUT_COOKIE : $loopThrough = $_COOKIE;
				break;
			case INPUT_SERVER : $loopThrough = $_SERVER;
				break;
			case INPUT_ENV : $loopThrough = $_ENV;
				break;
			case INPUT_REQUEST : $loopThrough = $_REQUEST;
				break;
		}

		// If $index is null, it means that the whole input type array is requested
		if (is_null($index))
		{
			$values = [];
			foreach ($loopThrough as $key => $value)
			{
				$values[$key] = is_array($value) ? $this->fetchGlobal($type, $key, $filter, $flags) : filter_var($value, $filter, $flags);
			}

			return $values;
		}

		// allow fetching multiple keys at once
		if (is_array($index))
		{
			$output = [];

			foreach ($index as $key)
			{
				$output[$key] = $this->fetchGlobal($type, $key, $filter, $flags);
			}

			return $output;
		}

		// Does the index contain array notation?
		if (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1)
		{
			$value = $loopThrough;
			for ($i = 0; $i < $count; $i++)
			{
				$key = trim($matches[0][$i], '[]');

				if ($key === '') // Empty notation will return the value as array
				{
					break;
				}

				if (isset($value[$key]))
				{
					$value = $value[$key];
				}
				else
				{
					return null;
				}
			}
		}

		// Due to issues with FastCGI and testing,
		// we need to do these all manually instead
		// of the simpler filter_input();
		if (empty($value))
		{
			$value = $loopThrough[$index] ?? null;
		}

		if (is_array($value) || is_object($value) || is_null($value))
		{
			return $value;
		}

		return filter_var($value, $filter, $flags);
	}

	//--------------------------------------------------------------------
}
