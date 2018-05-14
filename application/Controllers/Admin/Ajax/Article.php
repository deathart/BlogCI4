<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\ArticleModel;
use CodeIgniter\HTTP\Response;

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
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->article_model = new ArticleModel();
    }

    /**
     * @return Response
     */
    public function add():Response
    {
        $picture_one = str_replace('C:\\fakepath\\', 'uploads/', $_POST['pic']);
        $article_id = $this->article_model->Add($_POST['title'], $_POST['link'], $_POST['content'], $_POST['wordkey'], $_POST['cat'], $picture_one, $_POST['important']);

        return $this->Responded(['code' => 1, 'title' => "Création d'article", 'message' => 'Article en attente de correction, redirection en cours', 'article_id' => $article_id]);
    }

    /**
     * @return Response
     */
    public function edit():Response
    {
        $this->article_model->Edit($_POST['postid'], $_POST['title'], $_POST['link'], $_POST['content'], $_POST['wordkey'], $_POST['cat'], $_POST['pic'], $_POST['important'], $_POST['type']);
        if ($_POST['type'] === 0) {
            return $this->Responded(['code' => 1, 'title' => "Edition d'article", 'message' => 'Article modifié']);
        }
        if ($_POST['type'] === 1) {
            return $this->Responded(['code' => 1, 'title' => "Edition d'article", 'message' => 'Article corrigé, il est maintenant publié']);
        }
        if ($_POST['type'] === 2) {
            return $this->Responded(['code' => 1, 'title' => "Edition d'article", 'message' => 'Article corrigé, il est en attente de correction']);
        }
        if ($_POST['type'] === 3) {
            return $this->Responded(['code' => 1, 'title' => "Edition d'article", 'message' => 'Article corrigé, il est en attente de publication']);
        }

        return $this->Responded(['code' => 0, 'message' => 'Erreurs...']);
    }
}
