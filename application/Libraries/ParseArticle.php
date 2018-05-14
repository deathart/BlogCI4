<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Libraries;

use \App\Models\Blog\MediaModel;

/**
 * Class ParseArticle
 *
 * @package App\Libraries
 */
class ParseArticle
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var \App\Models\Blog\MediaModel
     */
    protected $media;

    /**
     * @var array
     */
    private $search = ['#\[b\](.*)\[/b\]#', '#\[i\](.*)\[/i\]#', '#\[u\](.*)\[/u\]#', '#\[del\](.*)\[/del\]#', '#\[list\](.*)\[/list\]#isU', '#\[\*\](.+)\[\/\*\]#i', '#\[quote\](.*)\[/quote\]#'];
    /**
     * @var array
     */
    private $replace = ['<strong>$1</strong>', '<em>$1</em>', '<u>$1</u>', '<del>$1</del>', '<ul>$1</ul>', '<li>$1</li>', '<blockquote>$1</blockquote>'];
    /**
     * @var bool
     */
    private $noparse;

    /**
     * ParseArticle constructor.
     *
     * @param string $content
     * @param bool $noparse
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(string $content, bool $noparse)
    {
        $this->content = $content;
        $this->noparse = $noparse;
        $this->media = new MediaModel();
    }

    /**
     * @return string
     */
    public function rendered(): string
    {
        $this->content = $this->parse_code();
        $this->content = $this->parse_htmlbasic();
        $this->content = $this->parse_header();
        $this->content = $this->parse_media();
        $this->content = $this->parse_href();
        $this->content = $this->parse_color();
        $this->content = $this->parse_align();
        $this->content = $this->parse_source();
        $this->content = $this->parse_alert();

        $this->content = str_replace(['[br]', '[hr]', "\n", "\r"], ['<br />', '<hr />', '<br />', ''], $this->content);

        if ($this->noparse) {
            $this->content = preg_replace("/\[(.*?)\]/is", '', $this->content);
        }

        return $this->content;
    }

    /**
     * @return string
     */
    private function parse_media(): string
    {
        $this->content = preg_replace_callback(
            '`\[img id="(.+)" width="(.+)" height="(.+)"\]`isU',
            function ($matches) {
                if (!$this->noparse) {
                    $get_info = $this->media->get_link($matches[1]);
                    $width = 'auto';
                    $height = 'auto';
                    if ($matches[2] != 0) {
                        $width = $matches[2] . 'px';
                    }
                    if ($matches[3] != 0) {
                        $height = $matches[3] . 'px';
                    }

                    return '<img name="' . $get_info->name . '" src="' . base_url($get_info->slug . '/' . $get_info->name) . '" class="img-fluid" style="width: ' . $width . ';height: ' . $height . ';" />';
                }
            },
            $this->content
        );

        $this->content = preg_replace_callback(
            '`\[youtube](.*)\[/youtube\]`siU',
            function ($matches) {
                if (!$this->noparse) {
                    $url = str_replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/', $matches[1]);

                    return '<iframe width="560" height="315" src="' . $url . '" frameborder="0" allowfullscreen></iframe>';
                }
            },
            $this->content
        );

        return $this->content;
    }

    /**
     * @return string
     */
    private function parse_href(): string
    {
        return preg_replace_callback(
            '`\[link="(.*)"\](.*)\[/link\]`siU',
            function ($matches) {
                if (!$this->noparse) {
                    return '<a href="' . $matches[1] . '" target="_blank" rel="nofollow">' . $matches[2] . '</a>';
                }
            },
            $this->content
        );
    }

    /**
     * @return string
     */
    private function parse_htmlbasic(): string
    {
        return preg_replace($this->search, $this->replace, $this->content);
    }

    /**
     * @return string
     */
    private function parse_code(): string
    {
        return preg_replace_callback(
            '`\[code="(.*)"\](.*)\[/code\]`siU',
            function ($matches) {
                if (!$this->noparse) {
                    $content_code = str_replace('<', '&lt;', $matches[2]);

                    return '<pre><code class="language-' . $matches[1] . '">' . $content_code . '</code></pre>';
                }
            },
            $this->content
        );
    }

    /**
     * @return string
     */
    private function parse_header(): string
    {
        if (!$this->noparse) {
            return preg_replace('#\[header="(.*)"\](.*)\[/header\]#siU', '<$1>$2</$1>', $this->content);
        }

        return preg_replace('#\[header="(.*)"\](.*)\[/header\]#siU', '$2', $this->content);
    }

    /**
     * @return string
     */
    private function parse_align(): string
    {
        return preg_replace_callback('#\[align="(.*)"\](.*)\[/align\]#siU', function ($matchs) {
            $cont = '';
            switch ($matchs[1]) {
                case 'left':
                    $cont = '<div style="text-align: left;">' . $matchs[2] . '</div>';

                break;
                case 'right':
                    $cont = '<div style="text-align: right;">' . $matchs[2] . '</div>';

                break;
                case 'center':
                    $cont = '<div style="text-align: center;">' . $matchs[2] . '</div>';

                break;
            }

            return $cont;
        }, $this->content);
    }

    /**
     * @return string
     */
    private function parse_color(): string
    {
        return preg_replace('#\[color="(.*)"\](.*)\[/color\]#siU', '<span style="color: $1;">$2</span>', $this->content);
    }

    /**
     * @return string
     */
    private function parse_source(): string
    {
        return preg_replace_callback(
            '`\[source url="(.*)"\](.*)\[/source\]`siU',
            function ($matches) {
                if (!$this->noparse) {
                    return '<div class="source">Source : <a href="' . $matches[1] . '" target="_blank" rel="nofollow">' . $matches[2] . '</a></div>';
                }
            },
            $this->content
        );
    }

    /**
     * @return string
     */
    private function parse_alert(): string
    {
        return preg_replace_callback(
            '`\[alert="(.*)"\](.*)\[/alert\]`siU',
            function ($matches) {
                if (!$this->noparse) {
                    switch ($matches[1]) {
                        case 'success':
                            $icon = '<i class="fas fa-check"></i>';

                            break;
                        case 'info':
                            $icon = '<i class="fas fa-info"></i>';

                            break;
                        case 'warning':
                            $icon = '<i class="fas fa-exclamation-triangle"></i>';

                            break;
                        case 'danger':
                            $icon = '<i class="fas fa-times"></i>';

                            break;
                    }

                    return '<div class="alert ' . $matches[1] . '"><div class="icon">' . $icon . '</div><div class="message">' . $matches[2] . '</div></div>';
                }
            },
            $this->content
        );
    }
}
