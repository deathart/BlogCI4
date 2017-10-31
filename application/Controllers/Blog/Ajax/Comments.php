<?php namespace App\Controllers\Blog\Ajax;

use App\Models\Blog\CommentsModel;

/**
 * Class Comments
 *
 * @package App\Controllers\Blog\Ajax
 */
class Comments extends Ajax
{

    /**
     * @var \App\Models\Blog\CommentsModel
     */
    private $comments_model;

    /**
     * Comments constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->comments_model = new CommentsModel();
    }

    /**
     * @return bool
     */
    public function AddComments()
    {
        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->request->isValidIP($this->request->getIPAddress())) {
                $this->comments_model->AddComments($_POST['post_id'], $_POST['name'], $_POST['email'], $_POST['message'], $this->request->getIPAddress());
                $this->Render(["message" => "Le commentaire à été envoyez à la modération, Rechargement de la page", "code" => 1]);
                return true;
            } else {
                $this->Render(["message" => "Error : yout IP is bizzar ?"], true);
                return false;
            }
        } else {
            $this->Render(["message" => "Error CSRF, You are HACKER ?"], true);
            return false;
        }
    }
}
