<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

use App\Models\Admin\ArticleModel;
use App\Models\Admin\CategoriesModel;

/**
 * Class Article
 *
 * @package App\Controllers\Admin
 */
class Article extends Application
{
    /**
     * @var \App\Models\Admin\ArticleModel
     */
    private $article_model;

    /**
     * @var \App\Models\Admin\CategoriesModel
     */
    private $categories_model;

    /**
     * Article constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->article_model    = new ArticleModel();
        $this->categories_model    = new CategoriesModel();
        $this->stitle        = 'Articles';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Article|string
     */
    public function index(): self
    {
        $this->data['last_five'] = $this->article_model->lastFive();
        $this->data['count_article'] = [
            'pub'       => $this->article_model->count_publied(),
            'att_co'    => $this->article_model->count_attCorrect(),
            'att_pub'   => $this->article_model->count_attPublished(),
            'brouillon' => $this->article_model->count_brouillon()
        ];

        return $this->render('article/home');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Article|string
     */
    public function add(): self
    {
        $this->tpage = "Ajout d'un article";
        $this->data['getcat'] = $this->categories_model->getlist();

        return $this->render('article/add', 'Ajouter un article');
    }

    /**
     * @param int $id id of article
     * @param int $type (1 = publied, 2 = wait corrected, 3 = wait publied, 4 = brouillon)
     *
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Article|string
     */
    public function edit(int $id, int $type): self
    {
        $this->tpage = "Modification d'article";
        $this->data['get_article'] = $this->article_model->GetArticle('id', $id);
        $this->data['getcat'] = $this->categories_model->getlist();
        $this->data['type'] = $type;

        return $this->render('article/edit', 'Modification de l\'article');
    }

    /**
     * @param int $id (1 = publied, 2 = wait corrected, 3 = wait publied, 4 = brouillon)
     *
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Article|string
     */
    public function list(int $id): self
    {
        $this->tpage = 'Liste des articles';
        $this->data['get_list'] = $this->article_model->getArticleListAdmin($id);
        $this->data['type'] = $id;
        $this->tpage = 'Liste des articles';

        return $this->render('article/list', 'Liste des articles');
    }
}
