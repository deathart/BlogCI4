<?php namespace App\Controllers\Admin;

use App\Models\Admin\CommentsModel;

class Comments extends Application
{
    protected $comments_model;

    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->comments_model = new CommentsModel();
        $this->stitle = 'Commentaires';
    }

    public function index(): self
    {
        $this->data['count_wait'] = $this->comments_model->countComments('0');
        $this->data['count_ok'] = $this->comments_model->countComments('1');
        $this->data['count_no'] = $this->comments_model->countComments('-1');

        return $this->render('comments/home');
    }

    public function wait(): self
    {
        $this->tpage = 'Commentaires en attentes de validation';
        $this->data['comments'] = $this->comments_model->getComments('0');

        return $this->render('comments/wait');
    }

    public function ok(): self
    {
        $this->tpage = 'Commentaires validés';
        $this->data['comments'] = $this->comments_model->getComments('1');

        return $this->render('comments/ok');
    }

    public function no(): self
    {
        $this->tpage = 'Commentaires refusés';
        $this->data['comments'] = $this->comments_model->getComments('-1');

        return $this->render('comments/no');
    }
}
