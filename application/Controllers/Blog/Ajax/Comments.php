<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog\Ajax;

use App\Libraries\Captcha;
use App\Models\Blog\CommentsModel;
use CodeIgniter\HTTP\Response;

/**
 * Class Comments
 *
 * @package App\Controllers\Blog\Ajax
 */
class Comments extends Ajax
{
    /**
     * @var \App\Libraries\Captcha
     */
    protected $captcha;

    /**
     * @var \App\Models\Blog\CommentsModel
     */
    private $comments_model;

    /**
     * Comments constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->comments_model = new CommentsModel();
        $this->captcha = new Captcha();
    }

    /**
     * @return Response
     */
    public function AddComments(): Response
    {
        $this->comments_model->AddComments($_POST['post_id'], $_POST['name'], $_POST['email'], $_POST['message'], $this->request->getIPAddress());

        return $this->Render(['message' => 'Le commentaire à été envoyez à la modération, Rechargement de la page', 'code' => 1]);
    }

    /**
     * @return Response
     */
    public function checkcaptcha(): Response
    {
        if ($this->captcha->Check($_POST['captcha'])) {
            $this->captcha->Remove();

            return $this->Render(['message' => 'Le commentaire à été envoyez à la modération, Rechargement de la page', 'code' => 1]);
        }

        return $this->Render(['message' => 'Error : Captcha is incorrect', 'code' => 2]);
    }
}
