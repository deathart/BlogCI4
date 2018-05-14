<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\CommentsModel;
use CodeIgniter\HTTP\Response;

/**
 * Class Comments
 *
 * @package App\Controllers\Admin\Ajax
 */
class Comments extends Ajax
{
    /**
     * @var \App\Models\Admin\CommentsModel
     */
    protected $comments_model;

    /**
     * Comments constructor.
     *
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->comments_model = new CommentsModel();
    }

    /**
     * @return Response
     */
    public function valide():Response
    {
        $this->comments_model->valideComments($_POST['id']);

        return $this->Responded(['code' => 1, 'title' => 'Commentaire', 'message' => 'Commentaire validé avec success']);
    }

    /**
     * @return Response
     */
    public function refuse():Response
    {
        $this->comments_model->refuseComments($_POST['id']);

        return $this->Responded(['code' => 1, 'title' => 'Commentaire', 'message' => 'Commentaire refusé avec success']);
    }
}
