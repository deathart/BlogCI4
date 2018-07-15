<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

// --------------------------------------------------------------------

/**
 * CodeIgniter Cookie Helpers
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      CodeIgniter Dev Team
 * @link        https://codeigniter.com/user_guide/helpers/cookie_helper.html
 */
if ( ! function_exists('set_cookie'))
{
	/**
	 * Set cookie
	 *
	 * Accepts seven parameters, or you can submit an associative
	 * array in the first parameter containing all the values.
	 *
	 * @param   array|string $name     Cookie name or array containing binds
	 * @param   string       $value    The value of the cookie
	 * @param   string       $expire   The number of seconds until expiration
	 * @param   string       $domain   For site-wide cookie.
	 *                                 Usually: .yourdomain.com
	 * @param   string       $path     The cookie path
	 * @param   string       $prefix   The cookie prefix
	 * @param   bool         $secure   True makes the cookie secure
	 * @param   bool         $httpOnly True makes the cookie accessible via
	 *                                 http(s) only (no javascript)
	 *
	 * @see     (\Config\Services::response())->setCookie()
	 * @see     \CodeIgniter\HTTP\Response::setCookie()
	 */
	function set_cookie($name, string $value = '', string $expire = '', string $domain = '', string $path = '/', string $prefix = '', bool $secure = false, bool $httpOnly = false)
	{
		// The following line shows as a syntax error in NetBeans IDE
		//(\Config\Services::response())->setcookie
		$response = \Config\Services::response();
		$response->setcookie($name, $value, $expire, $domain, $path, $prefix, $secure, $httpOnly);
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('get_cookie'))
{
	/**
	 * Fetch an item from the COOKIE array
	 *
	 * @param   string $index
	 * @param   bool   $xssClean
	 *
	 * @see     (\Config\Services::request())->getCookie()
	 * @see     \CodeIgniter\HTTP\IncomingRequest::getCookie()
	 * @return  mixed
	 */
	function get_cookie($index, bool $xssClean = false)
	{
		$app = config(\Config\App::class);
		$appCookiePrefix = $app->cookiePrefix;
		$prefix = isset($_COOKIE[$index]) ? '' : $appCookiePrefix;

		$request = \Config\Services::request();
		$filter = true === $xssClean ? FILTER_SANITIZE_STRING : null;
		$cookie = $request->getCookie($prefix . $index, $filter);

		return $cookie;
	}
}

//--------------------------------------------------------------------

if ( ! function_exists('delete_cookie'))
{
	/**
	 * Delete a COOKIE
	 *
	 * @param   mixed   $name
	 * @param   string  $domain  the cookie domain. Usually: .yourdomain.com
	 * @param   string  $path the cookie path
	 * @param   string  $prefix  the cookie prefix
	 * @see     (\Config\Services::response())->setCookie()
	 * @see     \CodeIgniter\HTTP\Response::setcookie()
	 * @return  void
	 */
	function delete_cookie($name, string $domain = '', string $path = '/', string $prefix = '')
	{
		set_cookie($name, '', '', $domain, $path, $prefix);
	}
}
