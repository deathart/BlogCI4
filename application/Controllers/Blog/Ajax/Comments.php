<?php namespace App\Controllers\Blog\Ajax;

use App\Libraries\Captcha;
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
     * @var \App\Libraries\Captcha
     */
    protected $captcha;

    /**
     * Comments constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->comments_model = new CommentsModel();
        $this->captcha = new Captcha();
    }

    /**
     * @return bool
     */
    public function AddComments(): bool
    {
        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->request->isValidIP($this->request->getIPAddress())) {
                $this->comments_model->AddComments($_POST['post_id'], $_POST['name'], $_POST['email'], $_POST['message'], $this->request->getIPAddress());
                $this->Render(['message' => 'Le commentaire à été envoyez à la modération, Rechargement de la page', 'code' => 1]);

                return true;
            }
            $this->Render(['message' => 'Error : your IP is bizzar ?', 'code' => 2]);

            return false;
        }
        $this->Render(['message' => 'Error CSRF, You are HACKER ?', 'code' => 2]);

        return false;
    }

    /**
     * @return bool
     */
    public function checkcaptcha(): bool
    {
        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->request->isValidIP($this->request->getIPAddress())) {
                if ($this->captcha->Check($_POST['captcha'])) {
                    $this->captcha->Remove();
                    $this->Render(['message' => 'Le commentaire à été envoyez à la modération, Rechargement de la page', 'code' => 1]);

                    return true;
                }
                $this->Render(['message' => 'Error : Captcha is incorrect', 'code' => 2]);

                return false;
            }
            $this->Render(['message' => 'Error : your IP is bizzar ?', 'code' => 2]);

            return false;
        }
        $this->Render(['message' => 'Error CSRF, You are HACKER ?', 'code' => 2]);

        return false;
    }
}
