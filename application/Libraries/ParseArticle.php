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
    private $search = ['#\[b\](.*)\[/b\]#', '#\[i\](.*)\[/i\]#', '#\[u\](.*)\[/u\]#', '#\[del\](.*)\[/del\]#'];
    /**
     * @var array
     */
    private $replace = ['<strong>$1</strong>', '<em>$1</em>', '<u>$1</u>', '<del>$1</del>'];

    /**
     * ParseArticle constructor.
     */
    public function __construct()
    {
        $this->media = new MediaModel();
    }

    /**
     * @param string $content
     *
     * @return mixed|string
     */
    public function rendered(string $content)
    {
        $content = $this->parse_code($content);
        $content = $this->parse_htmlbasic($content);
        $content = $this->parse_header($content);
        $content = $this->parse_img($content);
        $content = $this->parse_href($content);
        $content = $this->parse_color($content);
        $content = $this->parse_align($content);
        return $content;
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_img(string $content)
    {
        return preg_replace_callback(
            '`\[img id="(.+)"\]`isU',
            function ($matches) {
                $get_info = $this->media->get_link($matches[1]);
                return '<img src="' . base_url($get_info->slug . '/' . $get_info->name) . '" />';
            },
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_href(string $content)
    {
        return preg_replace('/<a href="(.*?)">/', '<a href="$1" target="_blank" rel="nofollow">', $content);
    }

    /**
     * @param string $content
     *
     * @return null|string|string[]
     */
    protected function parse_htmlbasic(string $content)
    {
        return preg_replace($this->search, $this->replace, $content);
    }

    /**
     * @param string $content
     *
     * @return null|string|string[]
     */
    protected function parse_code(string $content)
    {
        $content = str_replace('<', '&lt;', $content);

        return preg_replace_callback(
            '`\[code="(.*)"\](.*)\[/code\]`siU',
            function ($matches) {
                return '<pre><code class="language-' . $matches[1] . '">' . $matches[2] . '</code></pre>';
            },
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return null|string|string[]
     */
    protected function parse_header(string $content)
    {
        return preg_replace('#\[header="(.*)"\](.*)\[/header\]#siU', '<$1>$2</$1>', $content);
    }

    /**
     * @param string $content
     *
     * @return null|string|string[]
     */
    protected function parse_align(string $content)
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
     * @return null|string|string[]
     */
    protected function parse_color(string $content)
    {
        return preg_replace('#\[color="(.*)"\](.*)\[/color\]#siU', '<span style="color: $1;">$2</span>', $content);
    }
}
