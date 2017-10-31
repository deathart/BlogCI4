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
     * Cat constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Article';
        $this->post_model = new ArticleModel();
        $this->cat_model  = new CatModel();
    }

    /**
     * @return string
     */
    public function index(): self
    {
        $this->data['get_cat'] = $this->cat_model->GetCat();
        return $this->render('cat/home');
    }

    /**
     * @param string $link
     *
     * @return string
     */
    public function View(string $link): self
    {
        $pager = Services::pager();
        $CatID = $this->cat_model->GetCatByLink($link);
        $this->data['info_cat'] = $CatID;

        $total_row = $this->post_model->nb_articleByCat($CatID->id);

        $perPage = 8;

        $page = (!empty($_GET['page']) ? $_GET['page'] : 1);

        $this->data['get_all'] = $this->post_model->GetArticleByCat($CatID->id, $perPage, $page);

        $this->data['pager'] = $pager->makeLinks($page, $perPage, $total_row, 'cat');

        return $this->render('cat/view');
    }
}
