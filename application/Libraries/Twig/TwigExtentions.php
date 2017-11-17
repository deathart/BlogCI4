<?php namespace App\Libraries\Twig;

use App\Libraries\General;
use App\Libraries\ParseArticle;
use App\Models\Blog\ConfigModel;
use Config\Services;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
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
     * @var string
     */
    private $langPath = APPPATH . "Language";
    /**
     * @var \Illuminate\Translation\Translator|null
     */
    private $translator = null;

    /**
     * TwigExtentions constructor.
     */
    public function __construct($templateFolder)
    {
        $this->request      = Services::request();
        $this->Config_model = new ConfigModel();
        $this->general      = new General();

        $this->translator = new Translator(
            new FileLoader(
                new Filesystem(),
                $this->langPath
            ),
            $this->Config_model->GetConfig("lang") . '/' . $templateFolder . '/'
        );

        $this->translator->setFallback($this->Config_model->GetConfig("lang") . '/' . $templateFolder);
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
            'trans' => new Twig_Function('trans', [$this, 'trans'], ['is_safe' => ['html']]),
            'trans_choice' => new Twig_Function('trans_choice', [$this, 'transChoice'], ['is_safe' => ['html']])
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

    /**
     * @param $content
     *
     * @return mixed|string
     */
    public function parseBbcode($content)
    {
        $parse = new ParseArticle();
        return $parse->rendered($content);
    }

    /**
     * @param $id
     * @param array $parameters
     * @param string $domain
     * @param null $locale
     *
     * @return array|null|string
     */
    public function trans($id, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param $id
     * @param $number
     * @param array $parameters
     * @param string $domain
     * @param null $locale
     *
     * @return string
     */
    public function transChoice($id, $number, array $parameters = [], $domain = 'messages', $locale = null)
    {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $locale);
    }
}
