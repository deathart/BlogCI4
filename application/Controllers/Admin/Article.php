<?php namespace App\Controllers\Admin;

use App\Models\Admin\CatModel;
use App\Models\Admin\ArticleModel;

class Article extends Application
{
    /**
     * @var \App\Models\Admin\ArticleModel
     */
    private $article_model;

    /**
     * @var \App\Models\Admin\CatModel
     */
    private $cat_model;

    /**
     * Article constructor.
     *
     * @param array ...$params
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->article_model    = new ArticleModel();
        $this->cat_model    = new CatModel();
        $this->stitle        = 'Articles';
    }

    /**
     * @return string
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
     * @return string
     */
    public function add(): self
    {
        $this->tpage = "Ajout d'un article";
        $this->data['getcat'] = $this->cat_model->getlist();
        return $this->render('article/add', 'Ajouter un article');
    }

    /**
     * @param int $id id of article
     * @param int $type (1 = publied, 2 = wait corrected, 3 = wait publied, 4 = brouillon)
     *
     * @return string
     */
    public function edit(int $id, int $type): self
    {
        $this->tpage = "Modification d'article";
        $this->data['get_article'] = $this->article_model->GetArticle('id', $id);
        $this->data['getcat'] = $this->cat_model->getlist();
        $this->data['type'] = $type;
        return $this->render('article/edit', 'Modification de l\'article');
    }

    /**
     * @param int $id (1 = publied, 2 = wait corrected, 3 = wait publied, 4 = brouillon)
     *
     * @return string
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
