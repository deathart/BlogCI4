<?php namespace App\Libraries\Twig;

use App\Models\Admin\ConfigModel;
use Twig_Environment;
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
     * Twig constructor.
     *
     * @param $templateFolder
     */
    public function __construct($templateFolder)
    {
        $config_model = new ConfigModel();
        $loader = new Twig_Loader_Filesystem(APPPATH . 'Views/' . $templateFolder);

        $dataconfig = [
            'autoescape' => false
        ];

        if ($config_model->GetConfig('cache') == 'on') {
            $dataconfig = [
                'cache' => WRITEPATH . 'cache',
                'auto_reload' => true,
                'autoescape' => false
            ];
        }

        $this->environment = new Twig_Environment($loader, $dataconfig);

        $this->environment->addExtension(new TwigExtentions($templateFolder));
    }

    /**
     * @param $file
     * @param $array
     * @param string $ext
     *
     * @return string
     */
    public function Rendered($file, $array, $ext = '.twig'): string
    {
        $template = $this->environment->load('page/' . $file . $ext);

        return $template->render($array);
    }
}
