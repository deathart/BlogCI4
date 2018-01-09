<?php namespace App\Controllers\Blog;

use App\Models\Blog\ArticleModel;

/**
 * Class Home
 *
 * @package App\Controllers\Blog
 */
class Home extends Application
{

    /**
     * @var \App\Models\Blog\ArticleModel
     */
    protected $Post_model;

    /**
     * Home constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Accueil';
        $this->Post_model = new ArticleModel();
    }

    /**
     * @return \App\Controllers\Blog\Home|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        $this->data['last_important'] = $this->Post_model->GetLastArticle(4, true);
        $this->data['last_article'] = $this->Post_model->GetLastArticle(8);

        return $this->render('home');
    }
}
