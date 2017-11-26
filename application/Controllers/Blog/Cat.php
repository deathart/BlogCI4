<?php namespace App\Controllers\Blog;

use App\Models\Blog\CatModel;
use App\Models\Blog\ArticleModel;
use Config\Services;

/**
 * Class Cat
 *
 * @package App\Controllers\Blog
 */
class Cat extends Application
{

    /**
     * @var \App\Models\Blog\ArticleModel
     */
    protected $article_model;
    /**
     * @var \App\Models\Blog\CatModel
     */
    protected $cat_model;

    /**
     * Cat constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Article';
        $this->article_model = new ArticleModel();
        $this->cat_model  = new CatModel();
    }

    /**
     * @return \App\Controllers\Blog\Cat
     */
    public function index(): self
    {
        $this->data['get_cat'] = $this->cat_model->GetCat();
        return $this->render('cat/home');
    }

    /**
     * @param string $slug
     *
     * @return \App\Controllers\Blog\Cat
     */
    public function View(string $slug): self
    {
        $pager = Services::pager();
        $CatID = $this->cat_model->GetCatByLink($slug);
        $this->data['info_cat'] = $CatID;

        $total_row = $this->article_model->nb_articleByCat($CatID->id);

        $perPage = 8;

        $page = (!empty($_GET['page']) ? $_GET['page'] : 1);

        $this->data['get_all'] = $this->article_model->GetArticleByCat($CatID->id, $perPage, $page);

        $this->data['pager'] = $pager->makeLinks($page, $perPage, $total_row, 'cat');

        return $this->render('cat/view');
    }
}
