<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Libraries\Twig;

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
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct($templateFolder)
    {
        $this->request      = Services::request();
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
            'uri_segment' => new Twig_Filter('uri_segment', [$this, 'filterSegment']),
            'asset_path' => new Twig_Filter('asset_path', [$this, 'asset_path'])
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
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @return mixed|string
     */
    public function parseBbcode($content, $noparse = false): string
    {
        return (new ParseArticle($content, $noparse))->rendered();
    }

    /**
     * @param $id
     * @param array $params
     *
     * @return string
     */
    public function trans($id, array $params = []): string
    {
        $folder = 'blog';

        if ($this->request->uri->getSegment(1) === 'admin') {
            $folder = 'admin';
        }

        return lang($folder . '/' . $id, $params, $this->Config_model->GetConfig('lang'));
    }

    /**
     * @param $filename
     * @return string
     */
    public function asset_path($filename)
    {
        $manifest_path = FCPATH . 'assets/rev-manifest.json';

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $manifest = [];
        }

        if (array_key_exists($filename, $manifest)) {
            return base_url('assets/' . $manifest[$filename]);
        }

        return base_url('assets/' . $filename);
    }
}
