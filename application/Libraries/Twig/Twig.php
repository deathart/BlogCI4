<?php namespace App\Libraries\Twig;

use App\Models\Admin\ConfigModel;
use Codeigniter\UnknownFileException;
use CodeIgniter\Files\Exceptions\FileNotFoundException;
use Twig_Environment;
use Twig_Error_Loader;
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

        $loader = new Twig_Loader_Filesystem(FCPATH . 'themes' . DIRECTORY_SEPARATOR . $templateFolder . DIRECTORY_SEPARATOR . $config_model->GetConfig('theme_' . $templateFolder) . DIRECTORY_SEPARATOR . 'templates');

        if ($config_model->GetConfig('cache') === 'on') {
            $dataconfig['cache'] = WRITEPATH . 'cache';
            $dataconfig['auto_reload'] = true;
        }

        $dataconfig['autoescape'] = false;

        $this->environment = new Twig_Environment($loader, $dataconfig);

        $this->environment->addExtension(new TwigExtentions($templateFolder));
    }

    /**
     * @param string $file
     * @param array $array
     *
     * @return string
     * @throws \Codeigniter\UnknownFileException
     * @throws \CodeIgniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function Rendered(string $file, array $array): string
    {
        try {
            $template = $this->environment->load('page/' . $file . $this->ext);
        } catch (Twig_Error_Loader $error_Loader) {
            throw new UnknownFileException($error_Loader);
            throw new FileNotFoundException($error_Loader);
        }

        return $template->render($array);
    }
}
