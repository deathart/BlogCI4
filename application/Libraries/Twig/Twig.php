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
     * @var array
     */
    private $dataconfig = [];

    /**
     * @var string
     */
    private $ext = '.twig';

    /**
     * Twig constructor.
     *
     * @param $templateFolder
     */
    public function __construct(string $templateFolder)
    {
        $config_model = new ConfigModel();

        $loader = new Twig_Loader_Filesystem(FCPATH . 'themes' . DIRECTORY_SEPARATOR . $templateFolder . DIRECTORY_SEPARATOR . $config_model->GetConfig('theme_' . $templateFolder));

        if ($config_model->GetConfig('cache') == 'on') {
            $this->dataconfig[] = [
                'cache' => WRITEPATH . 'cache',
                'auto_reload' => true
            ];
        }

        $this->dataconfig[] = [
            'autoescape' => false
        ];

        $this->environment = new Twig_Environment($loader, $this->dataconfig);

        $this->environment->addExtension(new TwigExtentions($templateFolder));
    }

    /**
     * @param $file
     * @param $array
     * @param string $ext
     *
     * @return string
     */
    public function Rendered(string $file, array $array): string
    {
        $template = $this->environment->load('page/' . $file . $this->ext);

        return $template->render($array);
    }
}
