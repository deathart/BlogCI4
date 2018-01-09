<?php namespace CodeIgniter\Pager;

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
interface PagerInterface
{

	/**
	 * Handles creating and displaying the
	 *
	 * @param string $group
	 * @param string $template The output template alias to render.
	 *
	 * @return string
	 */
	public function links(string $group = 'default', string $template = 'default'): string;

	//--------------------------------------------------------------------

	/**
	 * Creates simple Next/Previous links, instead of full pagination.
	 *
	 * @param string $group
	 * @param string $template
	 *
	 * @return string
	 */
	public function simpleLinks(string $group = 'default', string $template = 'default'): string;

	//--------------------------------------------------------------------

	/**
	 * Allows for a simple, manual, form of pagination where all of the data
	 * is provided by the user. The URL is the current URI.
	 *
	 * @param int    $page
	 * @param int    $perPage
	 * @param int    $total
	 * @param string $template The output template alias to render.
	 *
	 * @return string
	 */
	public function makeLinks(int $page, int $perPage, int $total, string $template = 'default'): string;

	//--------------------------------------------------------------------

	/**
	 * Stores a set of pagination data for later display. Most commonly used
	 * by the model to automate the process.
	 *
	 * @param string $group
	 * @param int    $page
	 * @param int    $perPage
	 * @param int    $total
	 *
	 * @return mixed
	 */
	public function store(string $group, int $page, int $perPage, int $total);

	//--------------------------------------------------------------------

	/**
	 * Sets the path that an aliased group of links will use.
	 *
	 * @param string $group
	 * @param string $path
	 *
	 * @return mixed
	 */
	public function setPath(string $path, string $group = 'default');

	//--------------------------------------------------------------------

	/**
	 * Returns the total number of pages.
	 *
	 * @param string|null $group
	 *
	 * @return int
	 */
	public function getPageCount(string $group = 'default'): int;

	//--------------------------------------------------------------------

	/**
	 * Returns the number of the current page of results.
	 *
	 * @param string|null $group
	 *
	 * @return int
	 */
	public function getCurrentPage(string $group = 'default'): int;

	//--------------------------------------------------------------------

	/**
	 * Tells whether this group of results has any more pages of results.
	 *
	 * @param string|null $group
	 *
	 * @return bool
	 */
	public function hasMore(string $group = 'default'): bool;

	//--------------------------------------------------------------------

	/**
	 * Returns the last page, if we have a total that we can calculate with.
	 *
	 * @param string $group
	 *
	 * @return int|null
	 */
	public function getLastPage(string $group = 'default');

	//--------------------------------------------------------------------

	/**
	 * Returns the full URI to the next page of results, or null.
	 *
	 * @param string $group
	 *
	 * @return string|null
	 */
	public function getNextPageURI(string $group = 'default');

	//--------------------------------------------------------------------

	/**
	 * Returns the full URL to the previous page of results, or null.
	 *
	 * @param string $group
	 *
	 * @return string|null
	 */
	public function getPreviousPageURI(string $group = 'default');

	//--------------------------------------------------------------------

	/**
	 * Returns the number of results per page that should be shown.
	 *
	 * @param string $group
	 *
	 * @return int
	 */
	public function getPerPage(string $group = 'default'): int;

	//--------------------------------------------------------------------

	/**
	 * Returns an array with details about the results, including
	 * total, per_page, current_page, last_page, next_url, prev_url, from, to.
	 * Does not include the actual data. This data is suitable for adding
	 * a 'data' object to with the result set and converting to JSON.
	 *
	 * @param string $group
	 *
	 * @return array
	 */
	public function getDetails(string $group = 'default'): array;

	//--------------------------------------------------------------------
}
