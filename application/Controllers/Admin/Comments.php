<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

use App\Models\Admin\CommentsModel;

/**
 * Class Comments
 *
 * @package App\Controllers\Admin
 */
class Comments extends Application
{
    /**
     * @var \App\Models\Admin\CommentsModel
     */
    protected $comments_model;

    /**
     * Comments constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->comments_model = new CommentsModel();
        $this->stitle = 'Commentaires';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Comments|string
     */
    public function index(): self
    {
        $this->data['count_wait'] = $this->comments_model->countComments('0');
        $this->data['count_ok'] = $this->comments_model->countComments('1');
        $this->data['count_no'] = $this->comments_model->countComments('-1');

        return $this->render('comments/home');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Comments|string
     */
    public function wait(): self
    {
        $this->tpage = 'Commentaires en attentes de validation';
        $this->data['comments'] = $this->comments_model->GetComments('0');

        return $this->render('comments/wait');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Comments|string
     */
    public function ok(): self
    {
        $this->tpage = 'Commentaires validés';
        $this->data['comments'] = $this->comments_model->GetComments('1');

        return $this->render('comments/ok');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Comments|string
     */
    public function no(): self
    {
        $this->tpage = 'Commentaires refusés';
        $this->data['comments'] = $this->comments_model->GetComments('-1');

        return $this->render('comments/no');
    }
}
