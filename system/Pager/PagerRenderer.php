<?php namespace CodeIgniter\Pager;

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
 * Class PagerRenderer
 *
 * This class is passed to the view that describes the pagination,
 * and is used to get the link information and provide utility
 * methods needed to work with pagination.
 *
 * @package CodeIgniter\Pager
 */
class PagerRenderer
{

	protected $first;
	protected $last;
	protected $current;
	protected $total;
	protected $pageCount;
	protected $uri;

	//--------------------------------------------------------------------

	public function __construct(array $details)
	{
		$this->first = 1;
		$this->last = $details['pageCount'];
		$this->current = $details['currentPage'];
		$this->total = $details['total'];
		$this->uri = $details['uri'];
		$this->pageCount = $details['pageCount'];
	}

	//--------------------------------------------------------------------

	/**
	 * Sets the total number of links that should appear on either
	 * side of the current page. Adjusts the first and last counts
	 * to reflect it.
	 *
	 * @param int $count
	 *
	 * @return PagerRenderer
	 */
	public function setSurroundCount(int $count)
	{
		$this->updatePages($count);

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Checks to see if there is a "previous" page before our "first" page.
	 *
	 * @return bool
	 */
	public function hasPrevious(): bool
	{
		return $this->first > 1;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a URL to the "previous" page. The previous page is NOT the
	 * page before the current page, but is the page just before the
	 * "first" page.
	 *
	 * You MUST call hasPrevious() first, or this value may be invalid.
	 *
	 * @return string|null
	 */
	public function getPrevious()
	{
		if ( ! $this->hasPrevious())
		{
			return null;
		}

		$uri = clone $this->uri;

		$uri->addQuery('page', $this->first - 1);

		return (string) $uri;
	}

	//--------------------------------------------------------------------

	/**
	 * Checks to see if there is a "next" page after our "last" page.
	 *
	 * @return bool
	 */
	public function hasNext(): bool
	{
		return $this->pageCount > $this->last;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns a URL to the "next" page. The next page is NOT, the
	 * page after the current page, but is the page that follows the
	 * "last" page.
	 *
	 * You MUST call hasNext() first, or this value may be invalid.
	 *
	 * @return string|null
	 */
	public function getNext()
	{
		if ( ! $this->hasNext())
		{
			return null;
		}

		$uri = clone $this->uri;

		$uri->addQuery('page', $this->last + 1);

		return (string) $uri;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the URI of the first page.
	 *
	 * @return string
	 */
	public function getFirst(): string
	{
		$uri = clone $this->uri;

		$uri->addQuery('page', 1);

		return (string) $uri;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the URI of the last page.
	 *
	 * @return string
	 */
	public function getLast(): string
	{
		$uri = clone $this->uri;

		$uri->addQuery('page', $this->pageCount);

		return (string) $uri;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns an array of links that should be displayed. Each link
	 * is represented by another array containing of the URI the link
	 * should go to, the title (number) of the link, and a boolean
	 * value representing whether this link is active or not.
	 *
	 * @return array
	 */
	public function links(): array
	{
		$links = [];

		$uri = clone $this->uri;

		for ($i = $this->first; $i <= $this->last; $i ++ )
		{
			$links[] = [
				'uri'	 => (string) $uri->addQuery('page', $i),
				'title'	 => (int) $i,
				'active' => ($i == $this->current)
			];
		}

		return $links;
	}

	//--------------------------------------------------------------------

	/**
	 * Updates the first and last pages based on $surroundCount,
	 * which is the number of links surrounding the active page
	 * to show.
	 *
	 * @param int|null $count
	 */
	protected function updatePages(int $count = null)
	{
		if (is_null($count))
		{
			return;
		}

		$this->first = $this->current - $count > 0 ? (int) ($this->current - $count) : 1;
		$this->last = $this->current + $count <= $this->pageCount ? (int) ($this->current + $count) : (int) $this->pageCount;
	}

	//--------------------------------------------------------------------
}
