<?php namespace App\Controllers\Admin;

/**
 * Class Config
 *
 * @package App\Controllers\Admin
 */
class Config extends Application
{
    /**
     * Article constructor.
     *
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Config';
    }

    /**
     * @return \App\Controllers\Admin\Config|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        return $this->render('config/home');
    }

    /**
     * @return \App\Controllers\Admin\Config|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function params(): self
    {
        $this->tpage = 'Paramêtres';
        $this->data['getall'] = $this->config_model->GetConfigAll();

        return $this->render('config/params');
    }
}
