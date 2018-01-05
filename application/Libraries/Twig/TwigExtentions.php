<?php namespace App\Libraries\Twig;

use App\Libraries\General;
use App\Libraries\ParseArticle;
use App\Models\Blog\ConfigModel;
use Config\Services;
use Twig_Extension;
use Twig_Filter;
use Twig_Function;

/**
 * Class TwigExtentions
 *
 * @package App\Libraries\Twig
 */
class TwigExtentions extends Twig_Extension
{

    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    private $request;
    /**
     * @var \App\Libraries\General
     */
    private $general;
    /**
     * @var \App\Models\Blog\ConfigModel
     */
    private $Config_model;

    /**
     * TwigExtentions constructor.
     *
     * @param $templateFolder
     */
    public function __construct($templateFolder)
    {
        $this->request      = Services::request();
        $this->Config_model = new ConfigModel();
        $this->general      = new General();

        service('Language', $this->Config_model->GetConfig('lang'));
    }

    /**
     * @return array
     */
    public function getFilters():array
    {
        return [
            'base_url' => new Twig_Filter('base_url', 'base_url'),
            'config' => new Twig_Filter('config', [$this, 'filterConfig']),
            'uri_segment' => new Twig_Filter('uri_segment', [$this, 'filterSegment'])
        ];
    }

    /**
     * @return array
     */
    public function getFunctions():array
    {
        return [
            'general' => new Twig_Function('general', [$this, 'functionGeneral'], ['is_safe' => ['html']]),
            'parse' => new Twig_Function('parse', [$this, 'parseBbcode'], ['is_safe' => ['html']]),
            'trans' => new Twig_Function('trans', [$this, 'trans'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    public function filterConfig($string)
    {
        return $this->Config_model->GetConfig($string);
    }

    /**
     * @param $string
     *
     * @return string
     */
    public function filterSegment($string):string
    {
        return $this->request->uri->getSegment($string);
    }

    /**
     * @param string $func
     * @param array $data
     *
     * @return mixed
     */
    public function functionGeneral(string $func, ...$data)
    {
        if ($data) {
            return $this->general->$func(...$data);
        }

        return $this->general->$func();
    }

    /**
     * @param $content
     * @param bool $noparse
     *
     * @return mixed|string
     */
    public function parseBbcode($content, $noparse = false)
    {
        $parse = new ParseArticle();

        return $parse->rendered($content, $noparse);
    }

    /**
     * @param $id
     * @param array $parameters
     *
     * @return string
     */
    public function trans($id, array $parameters = []): string
    {
        $folder = 'blog';

        if ($this->request->uri->getSegment(1) == 'admin') {
            $folder = 'admin';
        }

        return lang($folder . '/' . $id, $parameters);
    }
}
