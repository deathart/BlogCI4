<?php namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\CommentsModel;

class Comments extends Ajax
{
    protected $comments_model;
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

    public function valide()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->comments_model->valideComments($_POST['id']);

                return $this->responded(['code' => 1, 'title' => 'Commentaire', 'message' => 'Commentaire validé avec success']);
            }
        } else {
            return $this->responded([]);
        }
    }

    public function refuse()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->comments_model->refuseComments($_POST['id']);

                return $this->responded(['code' => 1, 'title' => 'Commentaire', 'message' => 'Commentaire refusé avec success']);
            }
        } else {
            return $this->responded([]);
        }
    }
}
