<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Libraries\Twig;

use App\Models\Admin\ConfigModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Twig_Environment;
use Twig_Error_Loader;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;

/**
 * Class General
 *
 * @package App\Libraries
 */
class Twig
{
    /**
     * @var \Twig_Environment
     */
    private $environment;

    /**
     * @var string
     */
    private $ext = '.twig';

    /**
     * Twig constructor.
     *
     * @param string $templateFolder
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(string $templateFolder)
    {
        $config_model = new ConfigModel();

        $loader = new Twig_Loader_Filesystem(ROOTPATH . 'resources/themes' . DIRECTORY_SEPARATOR . $templateFolder . DIRECTORY_SEPARATOR . $config_model->GetConfig('theme_' . $templateFolder) . DIRECTORY_SEPARATOR . 'templates');

        if ($config_model->GetConfig('cache') === 'on') {
            $dataconfig['cache'] = WRITEPATH . 'cache';
            $dataconfig['auto_reload'] = true;
        }

        if ($config_model->GetConfig('debug') === '1') {
            $dataconfig['debug'] = true;
        }

        $dataconfig['autoescape'] = false;

        $this->environment = new Twig_Environment($loader, $dataconfig);

        $this->environment->addExtension(new TwigExtentions($templateFolder));

        if ($config_model->GetConfig('debug') === '1') {
            $this->environment->addExtension(new Twig_Extension_Debug());
        }
    }

    /**
     * @param string $file
     * @param array $array
     *
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return string
     */
    public function Rendered(string $file, array $array): string
    {
        try {
            $template = $this->environment->load('page/' . $file . $this->ext);
        } catch (Twig_Error_Loader $error_Loader) {
            throw new PageNotFoundException($error_Loader);
        }

        return $template->render($array);
    }
}
