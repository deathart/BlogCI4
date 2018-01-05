<?php namespace App\Controllers\Blog;

use App\Models\Blog\ArticleModel;
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
     * About constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Tags';
        $this->article_model = new ArticleModel();
    }

    /**
     * @param string $tags
     *
     * @return \App\Controllers\Blog\Tags|string
     */
    public function index(string $tags): self
    {
        $pager = Services::pager();
        $perPage = 8;
        $page = (!empty($_GET['page']) ? $_GET['page'] : 1);
        $total_row = $this->article_model->GetArticleByTags($tags);

        $this->data['get_all'] = $this->article_model->GetArticleByTags($tags, $perPage, $page);
        $this->data['tags_name'] = $tags;
        $this->data['pager'] = $pager->makeLinks($page, $perPage, $total_row, 'cat');

        return $this->render('tags/view');
    }
}
