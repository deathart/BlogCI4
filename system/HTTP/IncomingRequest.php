<?php namespace CodeIgniter\HTTP;

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
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HTTP\Files\FileCollection;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Services;

/**
 * Class IncomingRequest
 *
 * Represents an incoming, getServer-side HTTP request.
 *
 * Per the HTTP specification, this interface includes properties for
 * each of the following:
 *
 * - Protocol version
 * - HTTP method
 * - URI
 * - Headers
 * - Message body
 *
 * Additionally, it encapsulates all data as it has arrived to the
 * application from the CGI and/or PHP environment, including:
 *
 * - The values represented in $_SERVER.
 * - Any cookies provided (generally via $_COOKIE)
 * - Query string arguments (generally via $_GET, or as parsed via parse_str())
 * - Upload files, if any (as represented by $_FILES)
 * - Deserialized body binds (generally from $_POST)
 *
 * @package CodeIgniter\HTTP
 */
class IncomingRequest extends Request
{

	/**
	 * Enable CSRF flag
	 *
	 * Enables a CSRF cookie token to be set.
	 * Set automatically based on Config setting.
	 *
	 * @var bool
	 */
	protected $enableCSRF = false;

	/**
	 * A \CodeIgniter\HTTPLite\URI instance.
	 *
	 * @var URI
	 */
	public $uri;

	/**
	 * File collection
	 *
	 * @var Files\FileCollection
	 */
	protected $files;

	/**
	 * Negotiator
	 *
	 * @var \CodeIgniter\HTTP\Negotiate
	 */
	protected $negotiate;

	/**
	 * The default Locale this request
	 * should operate under.
	 *
	 * @var string
	 */
	protected $defaultLocale;

	/**
	 * The current locale of the application.
	 * Default value is set in Config\App.php
	 *
	 * @var string
	 */
	protected $locale;

	/**
	 * Stores the valid locale codes.
	 *
	 * @var array
	 */
	protected $validLocales = [];

	/**
	 * @var \Config\App
	 */
	public $config;

	/**
	 * Holds the old data from a redirect.
	 * @var array
	 */
	protected $oldInput = [];

	/**
	 * @var \CodeIgniter\HTTP\UserAgent
	 */
	protected $userAgent;

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @param object                      $config
	 * @param URI                         $uri
	 * @param string                      $body
	 * @param \CodeIgniter\HTTP\UserAgent $userAgent
	 */
	public function __construct($config, $uri = null, $body = 'php://input', UserAgent $userAgent)
	{
		// Get our body from php://input
		if ($body == 'php://input')
		{
			$body = file_get_contents('php://input');
		}

		$this->body = $body;
		$this->config = $config;
		$this->userAgent = $userAgent;

		parent::__construct($config);

		$this->populateHeaders();

		$this->uri = $uri;

		$this->detectURI($config->uriProtocol, $config->baseURL);

		$this->validLocales = $config->supportedLocales;

		$this->detectLocale($config);
	}

	//--------------------------------------------------------------------

	/**
	 * Handles setting up the locale, perhaps auto-detecting through
	 * content negotiation.
	 *
	 * @param $config
	 */
	public function detectLocale($config)
	{
		$this->locale = $this->defaultLocale = $config->defaultLocale;

		if ( ! $config->negotiateLocale)
		{
			return;
		}

		$this->setLocale($this->negotiate('language', $config->supportedLocales));
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the default locale as set in Config\App.php
	 *
	 * @return string
	 */
	public function getDefaultLocale(): string
	{
		return $this->defaultLocale;
	}

	//--------------------------------------------------------------------

	/**
	 * Gets the current locale, with a fallback to the default
	 * locale if none is set.
	 *
	 * @return string
	 */
	public function getLocale(): string
	{
		return $this->locale ?? $this->defaultLocale;
	}

	//--------------------------------------------------------------------

	/**
	 * Sets the locale string for this request.
	 *
	 * @param string $locale
	 *
	 * @return IncomingRequest
	 */
	public function setLocale(string $locale)
	{
		// If it's not a valid locale, set it
		// to the default locale for the site.
		if ( ! in_array($locale, $this->validLocales))
		{
			$locale = $this->defaultLocale;
		}

		$this->locale = $locale;

		// If the intl extension is loaded, make sure
		// that we set the locale for it... if not, though,
		// don't worry about it.
		try
		{
			if (class_exists('\Locale', false))
			{
				\Locale::setDefault($locale);
			}
		} catch (\Exception $e)
		{

		}

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Determines if this request was made from the command line (CLI).
	 *
	 * @return bool
	 */
	public function isCLI(): bool
	{
		return (PHP_SAPI === 'cli' || defined('STDIN'));
	}

	//--------------------------------------------------------------------

	/**
	 * Test to see if a request contains the HTTP_X_REQUESTED_WITH header.
	 *
	 * @return bool
	 */
	public function isAJAX(): bool
	{
		return ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
	}

	//--------------------------------------------------------------------

	/**
	 * Attempts to detect if the current connection is secure through
	 * a few different methods.
	 *
	 * @return bool
	 */
	public function isSecure(): bool
	{
		if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
		{
			return true;
		}
		elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		{
			return true;
		}
		elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
		{
			return true;
		}

		return false;
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch an item from the $_REQUEST object. This is the simplest way
	 * to grab data from the request object and can be used in lieu of the
	 * other get* methods in most cases.
	 *
	 * @param null $index
	 * @param null $filter
	 * @param null $flags
	 *
	 * @return mixed
	 */
	public function getVar($index = null, $filter = null, $flags = null)
	{
		return $this->fetchGlobal(INPUT_REQUEST, $index, $filter, $flags);
	}

	//--------------------------------------------------------------------

	/**
	 * A convenience method that grabs the raw input stream and decodes
	 * the JSON into an array.
	 *
	 * If $assoc == true, then all objects in the response will be converted
	 * to associative arrays.
	 *
	 * @param bool $assoc   Whether to return objects as associative arrays
	 * @param int  $depth   How many levels deep to decode
	 * @param int  $options Bitmask of options
	 *
	 * @see http://php.net/manual/en/function.json-decode.php
	 *
	 * @return mixed
	 */
	public function getJSON(bool $assoc = false, int $depth = 512, int $options = 0)
	{
		return json_decode($this->body, $assoc, $depth, $options);
	}

	//--------------------------------------------------------------------

	/**
	 * A convenience method that grabs the raw input stream(send method in PUT, PATCH, DELETE) and decodes
	 * the String into an array.
	 *
	 * @return mixed
	 */
	public function getRawInput()
	{
		parse_str($this->body, $output);

		return $output;
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch an item from GET data.
	 *
	 * @param null $index  Index for item to fetch from $_GET.
	 * @param null $filter A filter name to apply.
	 * @param null $flags
	 *
	 * @return mixed
	 */
	public function getGet($index = null, $filter = null, $flags = null)
	{
		return $this->fetchGlobal(INPUT_GET, $index, $filter, $flags);
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch an item from POST.
	 *
	 * @param null $index  Index for item to fetch from $_POST.
	 * @param null $filter A filter name to apply
	 * @param null $flags
	 *
	 * @return mixed
	 */
	public function getPost($index = null, $filter = null, $flags = null)
	{
		return $this->fetchGlobal(INPUT_POST, $index, $filter, $flags);
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch an item from POST data with fallback to GET.
	 *
	 * @param null $index  Index for item to fetch from $_POST or $_GET
	 * @param null $filter A filter name to apply
	 * @param null $flags
	 *
	 * @return mixed
	 */
	public function getPostGet($index = null, $filter = null, $flags = null)
	{
		// Use $_POST directly here, since filter_has_var only
		// checks the initial POST data, not anything that might
		// have been added since.
		return isset($_POST[$index]) ? $this->getPost($index, $filter, $flags) : $this->getGet($index, $filter, $flags);
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch an item from GET data with fallback to POST.
	 *
	 * @param null $index  Index for item to be fetched from $_GET or $_POST
	 * @param null $filter A filter name to apply
	 * @param null $flags
	 *
	 * @return mixed
	 */
	public function getGetPost($index = null, $filter = null, $flags = null)
	{
		// Use $_GET directly here, since filter_has_var only
		// checks the initial GET data, not anything that might
		// have been added since.
		return isset($_GET[$index]) ? $this->getGet($index, $filter, $flags) : $this->getPost($index, $filter, $flags);
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch an item from the COOKIE array.
	 *
	 * @param null $index  Index for item to be fetched from $_COOKIE
	 * @param null $filter A filter name to be applied
	 * @param null $flags
	 *
	 * @return mixed
	 */
	public function getCookie($index = null, $filter = null, $flags = null)
	{
		return $this->fetchGlobal(INPUT_COOKIE, $index, $filter, $flags);
	}

	//--------------------------------------------------------------------

	/**
	 * Fetch the user agent string
	 *
	 * @param null $filter
	 *
	 * @return mixed
	 */
	public function getUserAgent()
	{
		return $this->userAgent;
	}

	//--------------------------------------------------------------------

	/**
	 * Attempts to get old Input data that has been flashed to the session
	 * with redirect_with_input(). It first checks for the data in the old
	 * POST data, then the old GET data.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function getOldInput(string $key)
	{
		// If the session hasn't been started, or no
		// data was previously saved, we're done.
		if (empty($_SESSION['_ci_old_input']))
			return;

		// Check for the value in the POST array first.
		if (isset($_SESSION['_ci_old_input']['post'][$key]))
		{
			return $_SESSION['_ci_old_input']['post'][$key];
		}

		// Next check in the GET array.
		if (isset($_SESSION['_ci_old_input']['get'][$key]))
		{
			return $_SESSION['_ci_old_input']['get'][$key];
		}
	}

	/**
	 * Returns an array of all files that have been uploaded with this
	 * request. Each file is represented by an UploadedFile instance.
	 *
	 * @return Files\FileCollection
	 */
	public function getFiles()
	{
		if (is_null($this->files))
		{
			$this->files = new FileCollection();
		}

		return $this->files->all(); // return all files
	}


	//--------------------------------------------------------------------

	/**
	 * Retrieves a single file by the name of the input field used
	 * to upload it.
	 *
	 * @param string $fileID
	 *
	 * @return UploadedFile|null
	 */
	public function getFile(string $fileID)
	{
		if (is_null($this->files))
		{
			$this->files = new FileCollection();
		}

		return $this->files->getFile($fileID);
	}

	//--------------------------------------------------------------------

	/**
	 * Sets up our URI object based on the information we have. This is
	 * either provided by the user in the baseURL Config setting, or
	 * determined from the environment as needed.
	 *
	 * @param string $protocol
	 * @param string $baseURL
	 */
	protected function detectURI($protocol, $baseURL)
	{
		$this->uri->setPath($this->detectPath($protocol));

		// It's possible the user forgot a trailing slash on their
		// baseURL, so let's help them out.
		$baseURL = ! empty($baseURL) ? rtrim($baseURL, '/ ') . '/' : $baseURL;

		// Based on our baseURL provided by the developer (if set)
		// set our current domain name, scheme
		if ( ! empty($baseURL))
		{
			// We cannot add the path here, otherwise it's possible
			// that the routing will not work correctly if we are
			// within a sub-folder scheme. So it's modified in
			// the
			$this->uri->setScheme(parse_url($baseURL, PHP_URL_SCHEME));
			$this->uri->setHost(parse_url($baseURL, PHP_URL_HOST));
			$this->uri->setPort(parse_url($baseURL, PHP_URL_PORT));
			$this->uri->resolveRelativeURI(parse_url($baseURL, PHP_URL_PATH));
		}
		else
		{
			throw FrameworkException::forEmptyBaseURL();
		}
	}

	//--------------------------------------------------------------------

	/**
	 * Based on the URIProtocol Config setting, will attempt to
	 * detect the path portion of the current URI.
	 *
	 * @param string $protocol
	 *
	 * @return string
	 */
	public function detectPath($protocol)
	{
		if (empty($protocol))
		{
			$protocol = 'REQUEST_URI';
		}

		switch ($protocol)
		{
			case 'REQUEST_URI':
				$path = $this->parseRequestURI();
				break;
			case 'QUERY_STRING':
				$path = $this->parseQueryString();
				break;
			case 'PATH_INFO':
			default:
				$path = $_SERVER[$protocol] ?? $this->parseRequestURI();
				break;
		}

		return $path;
	}

	//--------------------------------------------------------------------

	/**
	 * Provides a convenient way to work with the Negotiate class
	 * for content negotiation.
	 *
	 * @param string $type
	 * @param array  $supported
	 * @param bool   $strictMatch
	 *
	 * @return string
	 */
	public function negotiate(string $type, array $supported, bool $strictMatch = false)
	{
		if (is_null($this->negotiate))
		{
			$this->negotiate = Services::negotiator($this, true);
		}

		switch (strtolower($type))
		{
			case 'media':
				return $this->negotiate->media($supported, $strictMatch);
				break;
			case 'charset':
				return $this->negotiate->charset($supported);
				break;
			case 'encoding':
				return $this->negotiate->encoding($supported);
				break;
			case 'language':
				return $this->negotiate->language($supported);
				break;
		}

		throw new \InvalidArgumentException($type . ' is not a valid negotiation type.');
	}

	//--------------------------------------------------------------------

	/**
	 * Will parse the REQUEST_URI and automatically detect the URI from it,
	 * fixing the query string if necessary.
	 *
	 * @return string The URI it found.
	 */
	protected function parseRequestURI(): string
	{
		if ( ! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']))
		{
			return '';
		}

		// parse_url() returns false if no host is present, but the path or query string
		// contains a colon followed by a number
		$parts = parse_url('http://dummy' . $_SERVER['REQUEST_URI']);
		$query = $parts['query'] ?? '';
		$uri = $parts['path'] ?? '';

		if (isset($_SERVER['SCRIPT_NAME'][0]))
		{
			if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
			{
				$uri = (string) substr($uri, strlen($_SERVER['SCRIPT_NAME']));
			}
			elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
			{
				$uri = (string) substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
			}
		}

		// This section ensures that even on servers that require the URI to be in the query string (Nginx) a correct
		// URI is found, and also fixes the QUERY_STRING getServer var and $_GET array.
		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0)
		{
			$query = explode('?', $query, 2);
			$uri = $query[0];
			$_SERVER['QUERY_STRING'] = $query[1] ?? '';
		}
		else
		{
			$_SERVER['QUERY_STRING'] = $query;
		}

		parse_str($_SERVER['QUERY_STRING'], $_GET);

		if ($uri === '/' || $uri === '')
		{
			return '/';
		}

		return $this->removeRelativeDirectory($uri);
	}

	//--------------------------------------------------------------------

	/**
	 * Parse QUERY_STRING
	 *
	 * Will parse QUERY_STRING and automatically detect the URI from it.
	 *
	 * @return    string
	 */
	protected function parseQueryString(): string
	{
		$uri = $_SERVER['QUERY_STRING'] ?? @getenv('QUERY_STRING');

		if (trim($uri, '/') === '')
		{
			return '';
		}
		elseif (strncmp($uri, '/', 1) === 0)
		{
			$uri = explode('?', $uri, 2);
			$_SERVER['QUERY_STRING'] = $uri[1] ?? '';
			$uri = $uri[0];
		}

		parse_str($_SERVER['QUERY_STRING'], $_GET);

		return $this->removeRelativeDirectory($uri);
	}

	//--------------------------------------------------------------------

	/**
	 * Remove relative directory (../) and multi slashes (///)
	 *
	 * Do some final cleaning of the URI and return it, currently only used in self::_parse_request_uri()
	 *
	 * @param    string $uri
	 *
	 * @return    string
	 */
	protected function removeRelativeDirectory($uri)
	{
		$uris = [];
		$tok = strtok($uri, '/');
		while ($tok !== false)
		{
			if (( ! empty($tok) || $tok === '0') && $tok !== '..')
			{
				$uris[] = $tok;
			}
			$tok = strtok('/');
		}

		return implode('/', $uris);
	}

	// --------------------------------------------------------------------
}
