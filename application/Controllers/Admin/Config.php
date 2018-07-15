<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

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
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->stitle = 'Config';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Config|string
     */
    public function index(): self
    {
        return $this->render('config/home');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Config|string
     */
    public function params(): self
    {
        $this->tpage = 'Paramêtres';
        $this->data['getall'] = $this->config_model->GetConfigAll();

        return $this->render('config/params');
    }
}
