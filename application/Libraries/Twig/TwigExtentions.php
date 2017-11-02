<?php namespace App\Libraries\Twig;

use App\Libraries\General;
use App\Libraries\ParseArticle;
use CodeIgniter\HTTP\Response;
use App\Models\Blog\ConfigModel;
use Config\App;
use Config\Services;
use Twig_Extension;
use Twig_Filter;
use Twig_Function;

class TwigExtentions extends Twig_Extension
{

    /**
     * @var \Config\App
     */
    private $config;
    /**
     * @var \CodeIgniter\Session\Session
     */
    private $session;
    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    private $request;
    /**
     * @var \CodeIgniter\HTTP\Response
     */
    private $response;
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
     */
    public function __construct()
    {
        $this->config       = new App();
        $this->session      = Services::session($this->config);
        $this->request      = Services::request();
        $this->response     = new Response($this->config);
        $this->Config_model = new ConfigModel();
        $this->general      = new General();
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
            'parse' => new Twig_Function('parse', [$this, 'parseBbcode'], ['is_safe' => ['html']])
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
     * @param $func
     * @param $data
     *
     * @return mixed
     */
    public function functionGeneral($func, $data = null)
    {
        if ($data != null) {
            return $this->general->$func($data);
        }

        return $this->general->$func();
    }

    public function parseBbcode($content)
    {
        $parse = new ParseArticle();
        return $parse->rendered($content);
    }
}
