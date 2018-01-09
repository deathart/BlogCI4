<?php namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\ArticleModel;

/**
 * Class Article
 *
 * @package App\Controllers\Admin\Ajax
 */
class Article extends Ajax
{
    protected $data = [];

    /**
     * @var \App\Models\Admin\ArticleModel
     */
    protected $article_model;

    /**
     * Article constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->article_model = new ArticleModel();
    }

    /**
     * @return \CodeIgniter\HTTP\Response
     */
    public function add()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $picture_one = str_replace('C:\\fakepath\\', 'uploads/', $_POST['pic']);
                $article_id = $this->article_model->Add($_POST['title'], $_POST['link'], $_POST['content'], $_POST['wordkey'], $_POST['cat'], $picture_one, $_POST['important']);

                return $this->responded(['code' => 1, 'title' => "Création d'article", 'message' => 'Article en attente de correction, redirection en cours', 'article_id' => $article_id]);
            }

            return $this->responded(['code' => 0, 'message' => 'Erreurs...']);
        }

        return $this->responded([]);
    }

    /**
     * @return \CodeIgniter\HTTP\Response
     */
    public function edit()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->article_model->Edit($_POST['postid'], $_POST['title'], $_POST['link'], $_POST['content'], $_POST['wordkey'], $_POST['cat'], $_POST['pic'], $_POST['important'], $_POST['type']);
                if ($_POST['type'] == 0) {
                    return $this->responded(['code' => 1, 'title' => "Edition d'article", 'message' => 'Article modifié']);
                } elseif ($_POST['type'] == 1) {
                    return $this->responded(['code' => 1, 'title' => "Edition d'article", 'message' => 'Article corrigé, il est maintenant publié']);
                } elseif ($_POST['type'] == 2) {
                    return $this->responded(['code' => 1, 'title' => "Edition d'article", 'message' => 'Article corrigé, il est en attente de correction']);
                } elseif ($_POST['type'] == 3) {
                    return $this->responded(['code' => 1, 'title' => "Edition d'article", 'message' => 'Article corrigé, il est en attente de publication']);
                }

                return $this->responded(['code' => 0, 'message' => 'Erreurs...']);
            }

            return $this->responded(['code' => 0, 'message' => 'Erreurs...']);
        }

        return $this->responded([]);
    }
}
