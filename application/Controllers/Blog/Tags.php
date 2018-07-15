<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog;

use App\Models\Blog\ArticleModel;
use App\Models\Blog\TagsModel;
use Config\Services;

/**
 * Class Tags
 *
 * @package App\Controllers\Blog
 */
class Tags extends Application
{
    /**
     * @var \App\Models\Blog\ArticleModel
     */
    protected $article_model;

    /**
     * @var TagsModel
     */
    protected $tags_model;

    /**
     * About constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->stitle = 'Tags';
        $this->article_model = new ArticleModel();
        $this->tags_model    = new TagsModel();
    }

    /**
     * @param string $tags
     *
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Blog\Errors|\App\Controllers\Blog\Tags
     * @todo Add Error if tags is not found
     */
    public function index(string $tags)
    {
        $pager = Services::pager();
        $perPage = 8;
        $page = (!empty($_GET['page']) ? $_GET['page'] : 1);

        $tags = $this->tags_model->GetBySlug($tags);

        if(!$tags) {
            return redirect(base_url() . 'Errors/404');
        }

        $total_row = $this->article_model->GetArticleByTags($tags->id);

        $this->data['get_all'] = $this->article_model->GetArticleByTags($tags->id, $perPage, $page);
        $this->data['tags_name'] = $tags->title;
        $this->data['pager'] = $pager->makeLinks($page, $perPage, $total_row, 'categories');

        return $this->render('tags/view');
    }
}
