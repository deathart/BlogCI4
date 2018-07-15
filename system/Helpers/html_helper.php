<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

// --------------------------------------------------------------------

/**
 * CodeIgniter HTML Helper
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      CodeIgniter Dev Team
 * @link        https://codeigniter.com/user_guide/helpers/html_helper.html
 */
if ( ! function_exists('ul'))
{
	/**
	 * Unordered List
	 *
	 * Generates an HTML unordered list from an single or
	 * multi-dimensional array.
	 *
	 * @param   array   $list
	 * @param   string  $attributes  HTML attributes
	 * @return  string
	 */
	function ul(array $list, string $attributes = ''): string
	{
		return _list('ul', $list, $attributes);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('ol'))
{
	/**
	 * Ordered List
	 *
	 * Generates an HTML ordered list from an single or multi-dimensional array.
	 *
	 * @param   array   $list
	 * @param   string  $attributes  HTML attributes
	 * @return  string
	 */
	function ol(array $list, string $attributes = ''): string
	{
		return _list('ol', $list, $attributes);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('_list'))
{
	/**
	 * Generates the list
	 *
	 * Generates an HTML ordered list from an single or multi-dimensional array.
	 *
	 * @param   string   $type
	 * @param   mixed    $list
	 * @param   string   $attributes
	 * @param   int      $depth
	 * @return  string
	 */
	function _list(string $type = 'ul', $list = [], string $attributes = '', int $depth = 0): string
	{
		// Set the indentation based on the depth
		$out = str_repeat(' ', $depth)
				// Write the opening list tag
				. '<' . $type . stringify_attributes($attributes) . ">\n";

		// Cycle through the list elements.  If an array is
		// encountered we will recursively call _list()

		static $_last_list_item = '';
		foreach ($list as $key => $val)
		{
			$_last_list_item = $key;

			$out .= str_repeat(' ', $depth + 2) . '<li>';

			if ( ! is_array($val))
			{
				$out .= $val;
			}
			else
			{
				$out .= $_last_list_item
						. "\n"
						. _list($type, $val, '', $depth + 4)
						. str_repeat(' ', $depth + 2);
			}

			$out .= "</li>\n";
		}

		// Set the indentation for the closing tag and apply it
		return $out . str_repeat(' ', $depth) . '</' . $type . ">\n";
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('img'))
{
	/**
	 * Image
	 *
	 * Generates an image element
	 *
	 * @param   mixed   $src
	 * @param   bool    $indexPage
	 * @param   mixed  $attributes
	 * @return  string
	 */
	function img($src = '', bool $indexPage = false, $attributes = ''): string
	{
		if ( ! is_array($src))
		{
			$src = ['src' => $src];
		}

		//If there is no alt attribute defined, set it to an empty string.
		if ( ! isset($src['alt']))
		{
			$src['alt'] = '';
		}

		$img = '<img';

		foreach ($src as $k => $v)
		{
			//Include a protocol if nothing is explicitely defined.
			if ($k === 'src' && ! preg_match('#^([a-z]+:)?//#i', $v))
			{
				if ($indexPage === true)
				{
					$img .= ' src="' . site_url($v) . '"';
				}
				else
				{
					$img .= ' src="' . slash_item('baseURL') . $v . '"';
				}
			}
			else
			{
				$img .= ' ' . $k . '="' . $v . '"';
			}
		}

		return $img . stringify_attributes($attributes) . ' />';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('doctype'))
{
	/**
	 * Doctype
	 *
	 * Generates a page document type declaration
	 *
	 * Examples of valid options: html5, xhtml-11, xhtml-strict, xhtml-trans,
	 * xhtml-frame, html4-strict, html4-trans, and html4-frame.
	 * All values are saved in the doctypes config file.
	 *
	 * @param   string  $type    The doctype to be generated
	 * @return  string
	 */
	function doctype(string $type = 'html5'): string
	{
		$config = new \Config\DocTypes();
		$doctypes = $config->list;

		return $doctypes[$type] ?? false;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('script_tag'))
{
	/**
	 * Script
	 *
	 * Generates link to a JS file
	 *
	 * @param   mixed   $src        Script source or an array
	 * @param   bool    $indexPage  Should indexPage be added to the JS path
	 * @return  string
	 */
	function script_tag($src = '', bool $indexPage = false): string
	{
		$script = '<script ';
		if ( ! is_array($src))
		{
			$src = ['src' => $src];
		}

		foreach ($src as $k => $v)
		{
			if ($k === 'src' && ! preg_match('#^([a-z]+:)?//#i', $v))
			{
				if ($indexPage === true)
				{
					$script .= 'src="' . site_url($v) . '" ';
				}
				else
				{
					$script .= 'src="' . slash_item('baseURL') . $v . '" ';
				}
			}
			else
			{
				$script .= $k . '="' . $v . '" ';
			}
		}

		return $script . 'type="text/javascript"' . "></script>";
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('link_tag'))
{
	/**
	 * Link
	 *
	 * Generates link to a CSS file
	 *
	 * @param   mixed   $href       Stylesheet href or an array
	 * @param   string  $rel
	 * @param   string  $type
	 * @param   string  $title
	 * @param   string  $media
	 * @param   bool    $indexPage  should indexPage be added to the CSS path.
	 * @return  string
	 */
	function link_tag($href = '', string $rel = 'stylesheet', string $type = 'text/css', string $title = '', string $media = '', bool $indexPage = false): string
	{
		$link = '<link ';

		// extract fields if needed
		if (is_array($href))
		{
			$rel = $href['rel'] ?? $rel;
			$type = $href['type'] ?? $type;
			$title = $href['title'] ?? $title;
			$media = $href['media'] ?? $media;
			$indexPage = $href['indexPage'] ?? $indexPage;
			$href = $href['href'] ?? '';
		}

		if ( ! preg_match('#^([a-z]+:)?//#i', $href))
		{
			if ($indexPage === true)
			{
				$link .= 'href="' . site_url($href) . '" ';
			}
			else
			{
				$link .= 'href="' . slash_item('baseURL') . $href . '" ';
			}
		}
		else
			$link .= 'href="' . $href . '" ';

		$link .= 'rel="' . $rel . '" type="' . $type . '" ';

		if ($media !== '')
		{
			$link .= 'media="' . $media . '" ';
		}

		if ($title !== '')
		{
			$link .= 'title="' . $title . '" ';
		}

		return $link . "/>";
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('video'))
	{
		/**
		 * Video
		 *
		 * Geneartes a video element to embed videos. The video element can
		 * contain one or more video sources
		 *
		 * @param  mixed  $src     Either a source string or an array of sources
		 * @param  string $unsupportedMessage    The message to display
		 * 		if the media tag is not supported by the browser
		 * @param  string $attributes            HTML attributes
		 * @param  array  $tracks
		 * @param  bool   $indexPage
		 * @return string
		 *
		 */
		function video($src, string $unsupportedMessage = '', string $attributes = '', array $tracks = [], bool $indexPage = false): string
		{
			if (is_array($src))
			{
				return _media('video', $src, $unsupportedMessage, $attributes, $tracks);
			}

			$video = '<video';

			if (_has_protocol($src))
			{
				$video .= ' src="' . $src . '"';
			}
			elseif ($indexPage === true)
			{
				$video .= ' src="' . site_url($src) . '"';
			}
			else
			{
				$video .= ' src="' . slash_item('baseURL') . $src . '"';
			}

			if ($attributes !== '')
			{
				$video .= ' ' . $attributes;
			}

			$video .= ">\n";

			if ( ! empty($tracks))
			{
				foreach ($tracks as $track)
				{
					$video .= _space_indent() . $track . "\n";
				}
			}

			if ( ! empty($unsupportedMessage))
			{
				$video .= _space_indent()
						. $unsupportedMessage
						. "\n";
			}

			$video .= "</video>\n";

			return $video;
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('audio'))
	{
		/**
		 * Audio
		 *
		 * Generates an audio element to embed sounds
		 *
		 * @param  mixed  $src                Either a source string or an array of sources
		 * @param  string $unsupportedMessage The message to display if the media tag is not supported by the browser.
		 * @param  string $attributes         HTML attributes
		 * @param array   $tracks
		 * @param bool    $indexPage
		 *
		 * @return string
		 */
		function audio($src, string $unsupportedMessage = '', string $attributes = '', array $tracks = [], bool $indexPage = false): string
		{
			if (is_array($src))
			{
				return _media('audio', $src, $unsupportedMessage, $attributes, $tracks);
			}

			$audio = '<audio';

			if (_has_protocol($src))
			{
				$audio .= ' src="' . $src . '"';
			}
			elseif ($indexPage === true)
			{
				$audio .= ' src="' . site_url($src) . '"';
			}
			else
			{
				$audio .= ' src="' . slash_item('baseURL') . $src . '"';
			}

			if ($attributes !== '')
			{
				$audio .= ' ' . $attributes;
			}

			$audio .= '>';

			if ( ! empty($tracks))
			{
				foreach ($tracks as $track)
				{
					$audio .= "\n" . _space_indent() . $track;
				}
			}

			if ( ! empty($unsupportedMessage))
			{
				$audio .= "\n" . _space_indent() . $unsupportedMessage . "\n";
			}

			$audio .= "</audio>\n";

			return $audio;
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('_media'))
	{
		function _media(string $name, array $types = [], string $unsupportedMessage = '', string $attributes = '', array $tracks = []): string
		{
			$media = '<' . $name;

			if (empty($attributes))
			{
				$media .= '>';
			}
			else
			{
				$media .= ' ' . $attributes . '>';
			}

			$media .= "\n";

			foreach ($types as $option)
			{
				$media .= _space_indent() . $option . "\n";
			}

			if ( ! empty($tracks))
			{
				foreach ($tracks as $track)
				{
					$media .= _space_indent() . $track . "\n";
				}
			}

			if ( ! empty($unsupportedMessage))
			{
				$media .= _space_indent() . $unsupportedMessage . "\n";
			}

			$media .= '</' . $name . ">\n";

			return $media;
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('source'))
	{
		/**
		 * Source
		 *
		 * Generates a source element that specifies multiple media resources
		 * for either audio or video element
		 *
		 * @param  string $src          The path of the media resource
		 * @param  string $type         The MIME-type of the resource with
		 * optional codecs parameters
		 * @param  string $attributes   HTML attributes
		 * @param  bool $indexPage
		 * @return string
		 */
		function source(string $src, string $type = 'unknown', string $attributes = '', bool $indexPage = false): string
		{
			if ( ! _has_protocol($src))
				if ($indexPage === true)
				{
					$src = site_url($src);
				}
				else
				{
					$src = slash_item('baseURL') . $src;
				}

			$source = '<source src="' . $src
					. '" type="' . $type . '"';

			if ( ! empty($attributes))
			{
				$source .= ' ' . $attributes;
			}

			$source .= ' />';

			return $source;
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('track'))
	{
		/**
		 * Track
		 *
		 * Generates a track element to specify timed tracks. The tracks are
		 * formatted in WebVTT format.
		 *
		 * @param  string $src          The path of the .VTT file
		 * @param  string $kind
		 * @param  string $srcLanguage
		 * @param  string $label
		 * @return string
		 */
		function track(string $src, string $kind, string $srcLanguage, string $label): string
		{
			return '<track src="' . $src
					. '" kind="' . $kind
					. '" srclang="' . $srcLanguage
					. '" label="' . $label
					. '" />';
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('object'))
	{
		/**
		 * Object
		 *
		 * Generates an object element that represents the media
		 * as either image or a resource plugin such as audio, video,
		 * Java applets, ActiveX, PDF and Flash
		 *
		 * @param  string $data       A resource URL
		 * @param  string $type       Content-type of the resource
		 * @param  string $attributes HTML attributes
		 * @param  array  $params
		 * @param bool    $indexPage
		 *
		 * @return string
		 */
		function object(string $data, string $type = 'unknown', string $attributes = '', array $params = [], bool $indexPage = false): string
		{
			if ( ! _has_protocol($data))
				if ($indexPage === true)
				{
					$data = site_url($data);
				}
				else
				{
					$data = slash_item('baseURL') . $data;
				}

			$object = '<object data="' . $data . '" '
					. $attributes . '>';

			if ( ! empty($params))
			{
				$object .= "\n";
			}

			foreach ($params as $param)
			{
				$object .= _space_indent() . $param . "\n";
			}

			$object .= "</object>\n";

			return $object;
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('param'))
	{
		/**
		 * Param
		 *
		 * Generates a param element that defines parameters
		 * for the object element.
		 *
		 * @param  string $name        The name of the parameter
		 * @param  string $value       The value of the parameter
		 * @param  string $type        The MIME-type
		 * @param  string $attributes  HTML attributes
		 * @return string
		 */
		function param(string $name, string $value, string $type = 'ref', string $attributes = ''): string
		{
			return '<param name="' . $name
					. '" type="' . $type
					. '" value="' . $value
					. '" ' . $attributes . ' />';
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('embed'))
	{
		/**
		 * Embed
		 *
		 * Generates an embed element
		 *
		 * @param  string $src          The path of the resource to embed
		 * @param  string $type         MIME-type
		 * @param  string $attributes   HTML attributes
		 * @param  bool   $indexPage
		 * @return string
		 */
		function embed(string $src, string $type='unknown', string $attributes = '', bool $indexPage = false): string
		{
			if ( ! _has_protocol($src))
				if ($indexPage === true)
				{
					$src = site_url($src);
				}
				else
				{
					$src = slash_item('baseURL') . $src;
				}

			return '<embed src="' . $src
					. '" type="' . $type . '" '
					. $attributes . " />\n";
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('_has_protocol'))
	{
		function _has_protocol($url)
		{
			return preg_match('#^([a-z]+:)?//#i', $url);
		}
	}

	// ------------------------------------------------------------------------

	if ( ! function_exists('_space_indent'))
	{
		function _space_indent($depth = 2)
		{
			return str_repeat(' ', $depth);
		}
	}
}

// ------------------------------------------------------------------------
