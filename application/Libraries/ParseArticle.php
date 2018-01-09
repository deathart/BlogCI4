<?php
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

    private $noparse;

    /**
     * ParseArticle constructor.
     */
    public function __construct()
    {
        $this->media = new MediaModel();
    }

    /**
     * @param string $content
     * @param bool $noparse
     *
     * @return mixed|string
     */
    public function rendered(string $content, bool $noparse): string
    {
        $this->noparse = $noparse;

        $content = $this->parse_code($content);
        $content = $this->parse_htmlbasic($content);
        $content = $this->parse_header($content);
        $content = $this->parse_img($content);
        $content = $this->parse_href($content);
        $content = $this->parse_color($content);
        $content = $this->parse_align($content);

        $content = str_replace(['[br]', '[hr]', "\n", "\r"], ['<br />', '<hr />', '<br />', ''], $content);

        return $content;
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_img(string $content): string
    {
        return preg_replace_callback(
            '`\[img id="(.+)" width="(.+)" height="(.+)"\]`isU',
            function ($matches) {
                $get_info = $this->media->get_link($matches[1]);
                $width = '';
                $height = '';
                if ($matches[2] != 0) {
                    $width = $matches[2];
                }
                if ($matches[3] != 0) {
                    $height = $matches[3];
                }
                if (!$this->noparse) {
                    return '<img name="' . $get_info->name . '" src="' . base_url($get_info->slug . '/' . $get_info->name) . '" style="width: ' . $width . 'px;height: ' . $height . 'px;" />';
                }
            },
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_href(string $content): string
    {
        return preg_replace_callback(
            '`\[link="(.*)"\](.*)\[/link\]`siU',
            function ($matches) {
                if (!$this->noparse) {
                    return '<a href="' . $matches[1] . '" target="_blank" rel="nofollow">' . $matches[2] . '</a>';
                }
            },
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_htmlbasic(string $content): string
    {
        return preg_replace($this->search, $this->replace, $content);
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_code(string $content): string
    {
        return preg_replace_callback(
            '`\[code="(.*)"\](.*)\[/code\]`siU',
            function ($matches) {
                if (!$this->noparse) {
                    $content_code = str_replace('<', '&lt;', $matches[2]);

                    return '<pre><code class="language-' . $matches[1] . '">' . $content_code . '</code></pre>';
                }
            },
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_header(string $content): string
    {
        if (!$this->noparse) {
            return preg_replace('#\[header="(.*)"\](.*)\[/header\]#siU', '<$1>$2</$1>', $content);
        }

        return preg_replace('#\[header="(.*)"\](.*)\[/header\]#siU', '$2', $content);
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_align(string $content): string
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
        }, $content);
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_color(string $content): string
    {
        return preg_replace('#\[color="(.*)"\](.*)\[/color\]#siU', '<span style="color: $1;">$2</span>', $content);
    }
}
