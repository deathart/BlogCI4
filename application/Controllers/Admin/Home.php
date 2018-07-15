<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

/**
 * Class Home
 *
 * @package App\Controllers\Admin
 */
class Home extends Application
{
    /**
     * Home constructor.
     * 
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->stitle = 'Accueil';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Home|string
     */
    public function index(): self
    {
        return $this->render('home');
    }
}
