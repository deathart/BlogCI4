<?php namespace App\Libraries;

use App\Models\Blog\CatModel;
use App\Models\Blog\CommentsModel;
use App\Models\Blog\ArticleModel;

/**
 * Class General
 *
 * @package App\Libraries
 */
class General
{

    /**
     * @var \App\Models\Blog\CatModel
     */
    private $cat_model;
    /**
     * @var \App\Models\Blog\ArticleModel
     */
    private $article_model;

    /**
     * @var \App\Models\Blog\CommentsModel
     */
    private $comments_model;

    /**
     * General constructor.
     */
    public function __construct()
    {
        $this->cat_model = new CatModel();
        $this->article_model = new ArticleModel();
        $this->comments_model = new CommentsModel();
    }

    /**
     * @param string $json
     *
     * @return string
     */
    public function TradCat(string $json): string
    {
        $arr = '';
        $piece = explode(';', $json);
        foreach ($piece as $data) {
            $tt = $this->cat_model->GetCatNameAndLink($data);
            $arr .= "<a href='".base_url('cat/' . $tt->slug)."'>".$tt->title. '</a> ';
        }

        return $arr;
    }

    public function GetCat()
    {
        return $this->cat_model->GetCat();
    }

    /**
     * @param int $id post ID
     *
     * @return int
     */
    public function CountCom(int $id): int
    {
        return $this->comments_model->CountComs($id);
    }

    /**
     * @param int $cat car id
     *
     * @return int
     */
    public function CountArticle(int $cat): int
    {
        return $this->article_model->nb_articleByCat($cat);
    }

    /**
     * @param int $post post ID
     *
     * @return int
     */
    public function CountView(int $post): int
    {
        return $this->article_model->nb_PostView($post);
    }
}
