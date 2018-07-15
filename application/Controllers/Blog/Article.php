<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog;

use App\Libraries\Captcha;
use App\Models\Blog\ArticleModel;
use App\Models\Blog\CommentsModel;

/**
 * Class Article
 *
 * @package App\Controllers\Blog
 */
class Article extends Application
{
    /**
     * @var \App\Models\Blog\ArticleModel
     */
    protected $article_model;

    /**
     * @var \App\Models\Blog\CommentsModel
     */
    protected $comments_model;

    /**
     * @var \App\Libraries\Captcha
     */
    protected $captcha;

    /**
     * Article constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->stitle         = 'Article';
        $this->article_model  = new ArticleModel();
        $this->comments_model = new CommentsModel();
        $this->captcha        = new Captcha();
    }

    /**
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        return redirect('404');
    }

    /**
     * @param string $link
     *
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Blog\Article|string
     */
    public function View(string $link): self
    {
        $info = $this->article_model->GetArticle('link', $link);

        if ($this->request->isValidIP($this->request->getIPAddress())) {
            $this->article_model->add_view($info->id, $this->request->getIPAddress());
        }

        $this->data['get_info_article'] = $info;
        $this->data['get_coms'] = $this->comments_model->GetComs($info->id);
        $this->data['related_article'] = $this->article_model->GetRelated($info->id, $info->tags, 5);
        $this->data['captcha'] = $this->captcha->Create();

        return $this->render('article/view', $info->title, $info->tags);
    }
}
