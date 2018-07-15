<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\CategoriesModel;
use CodeIgniter\HTTP\Response;

/**
 * Class Categories
 *
 * @package App\Controllers\Admin\Ajax
 */
class Categories extends Ajax
{
    /**
     * @var \App\Models\Admin\CategoriesModel
     */
    protected $categories_model;

    /**
     * Article constructor.
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->categories_model = new CategoriesModel();
    }

    /**
     * @return Response
     */
    public function UpdateTitle():Response
    {
        $this->categories_model->UpdateCategories($_POST['id'], 'title', $_POST['title']);

        return $this->Responded(['code' => 1, 'title' => 'Catégorie modifier', 'message' => 'La catégories à bien été modifier']);
    }

    /**
     * @return Response
     */
    public function UpdateContent():Response
    {
        $this->categories_model->UpdateCategories($_POST['id'], 'description', $_POST['content']);

        return $this->Responded(['code' => 1, 'title' => 'Catégorie modifier', 'message' => 'La catégories à bien été modifier']);
    }

    /**
     * @return Response
     */
    public function Add():Response
    {
        $this->categories_model->AddCategories($_POST['title'], $_POST['content'], $_POST['slug'], $_POST['icon']);

        return $this->Responded(['code' => 1, 'title' => "Ajout d'une catégorie", 'message' => 'La catégories à bien été ajouter, rechargemen tde la page']);
    }
}
