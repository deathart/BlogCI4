<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog;

use App\Models\Blog\ArticleModel;
use App\Models\Blog\CategoriesModel;
use Config\Services;

/**
 * Class Categories
 *
 * @package App\Controllers\Blog
 */
class Categories extends Application
{
    /**
     * @var \App\Models\Blog\ArticleModel
     */
    protected $article_model;
    /**
     * @var \App\Models\Blog\CategoriesModel
     */
    protected $categories_model;

    /**
     * Categories constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->stitle = 'Article';
        $this->article_model = new ArticleModel();
        $this->categories_model  = new CategoriesModel();
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Blog\Categories|string
     */
    public function index(): self
    {
        $this->data['get_cat'] = $this->categories_model->GetCategories();

        return $this->render('categories/home');
    }

    /**
     * @param string $slug
     *
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Blog\Categories|string
     */
    public function View(string $slug): self
    {
        $pager = Services::pager();
        $CatID = $this->categories_model->GetCategoriesBySlug($slug);
        $this->data['info_cat'] = $CatID;

        $total_row = $this->article_model->nb_articleByCategories($CatID->id);

        $perPage = 8;

        $page = (!empty($_GET['page']) ? $_GET['page'] : 1);

        $this->data['get_all'] = $this->article_model->GetArticleByCategories($CatID->id, $perPage, $page);

        $this->data['pager'] = $pager->makeLinks($page, $perPage, $total_row, 'categories');

        return $this->render('categories/view');
    }
}
