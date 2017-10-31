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
        $content = $this->parse_img($content);
        $content = $this->parse_href($content);
        return $content;
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_img(string $content)
    {
        $content = preg_replace_callback(
            '`\[img id="(.+)"\]`isU',
            function ($matches) {
                $get_info = $this->media->get_link($matches[1]);
                return '<img src="' . base_url($get_info->slug . '/' . $get_info->name) . '" />';
            },
            $content
        );
        return $content;
    }

    /**
     * @param string $content
     *
     * @return mixed
     */
    protected function parse_href(string $content)
    {
        return preg_replace('/<a href="(.*?)">/', '<a href="$1" target="_blank">', $content);
    }
}
