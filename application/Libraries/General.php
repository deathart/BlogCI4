<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Libraries;

use App\Models\Admin\AuthModel;
use App\Models\Blog\ArticleModel;
use App\Models\Blog\CategoriesModel;
use App\Models\Blog\CommentsModel;
use App\Models\Blog\PagesModel;
use App\Models\Blog\TagsModel;
use Cocur\Slugify\Slugify;
use Config\App;
use Config\Services;

/**
 * Class General
 * @package App\Libraries
 */
class General
{
    /**
     * @var \CodeIgniter\Session\Session
     */
    private $session;

    /**
     * @var \App\Models\Blog\CategoriesModel
     */
    private $categories_model;

    /**
     * @var \App\Models\Blog\ArticleModel
     */
    private $article_model;

    /**
     * @var \App\Models\Blog\CommentsModel
     */
    private $comments_model;

    /**
     * @var \App\Models\Blog\PagesModel
     */
    private $pages_model;

    /**
     * @var \App\Models\Admin\AuthModel
     */
    private $Auth_model;

    /**
     * @var \App\Models\Blog\TagsModel
     */
    private $Tags_model;

    /**
     * General constructor.
     */
    public function __construct()
    {
        $this->session      = Services::session(new App());
        $this->categories_model = new CategoriesModel();
        $this->article_model = new ArticleModel();
        $this->comments_model = new CommentsModel();
        $this->pages_model = new PagesModel();
        $this->Auth_model   = new AuthModel();
        $this->Tags_model = new TagsModel();
    }

    /**
     * @param string $categories
     * @param string $separator
     * @return string
     */
    public function TradCat(string $categories, string $separator = null): string
    {
        $arr = '';
        $piece = explode(';', $categories);
        $lastKey = \count($piece) - 1;

        foreach ($piece as $k =>$data) {
            $tt = $this->categories_model->GetCategoriesNameAndLink($data);
            $arr .= "<a href='" . base_url('categories/' . $tt->slug) . "'>" . $tt->title . '</a>';

            if ($separator && $k !== $lastKey) {
                $arr .= '&nbsp;' . $separator . '&nbsp;';
            }
        }

        return $arr;
    }

    /**
     * @param string $tags
     * @return array
     */
    public function TradTags(string $tags): array
    {
        $tags = preg_replace('/\s+/', '', $tags);
        $arr = [];
        $piece = explode(',', $tags);
        foreach ($piece as $data) {
            $tagsdb = $this->Tags_model->GetById($data);
            $arr[] = ['title' => $tagsdb->title, 'slug' => $tagsdb->slug];
        }

        return $arr;
    }

    /**
     * @return array|mixed
     */
    public function GetCat()
    {
        return $this->categories_model->GetCategories();
    }

    /**
     * @return array|mixed
     */
    public function GetPages()
    {
        return $this->pages_model->GetPages();
    }

    /**
     * @param int $id post ID
     * @return int
     */
    public function CountCom(int $id): int
    {
        return $this->comments_model->CountComs($id);
    }

    /**
     * @param int $cat car id
     * @return int
     */
    public function CountArticle(int $cat): int
    {
        return $this->article_model->nb_articleByCategories($cat);
    }

    /**
     * @param int $post post ID
     * @return int
     */
    public function CountView(int $post): int
    {
        return $this->article_model->nb_PostView($post);
    }

    /**
     * @return string
     */
    public function getusername(): string
    {
        return $this->Auth_model->GetUsername($this->session->get('Account_id'));
    }

    /**
     * @return string
     */
    public function getavatar():string
    {
        return $this->Auth_model->GetAvatar($this->session->get('Account_id'));
    }

    /**
     * @param $data
     * @return string
     */
    public function slugify($data): string
    {
        return (new Slugify())->slugify($data);
    }
}
