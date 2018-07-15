<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

use App\Models\Admin\CategoriesModel;

/**
 * Class Categories
 *
 * @package App\Controllers\Admin
 */
class Categories extends Application
{
    /**
     * @var \App\Models\Admin\CategoriesModel
     */
    private $categories_model;

    /**
     * Categories constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->categories_model = new CategoriesModel();
        $this->stitle = 'Categories';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Categories|string
     */
    public function index(): self
    {
        $this->tpage = 'Liste des categories';
        $this->data['get_cat'] = $this->categories_model->getlist();

        return $this->render('categories/list');
    }
}
