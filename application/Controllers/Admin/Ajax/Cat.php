<?php namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\CatModel;
use CodeIgniter\HTTP\Response;

/**
 * Class Cat
 *
 * @package App\Controllers\Admin\Ajax
 */
class Cat extends Ajax
{

    /**
     * @var \App\Models\Admin\CatModel
     */
    protected $cat_model;

    /**
     * Article constructor.
     *
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->cat_model = new CatModel();
    }

    /**
     * @return Response
     */
    public function UpdateTitle():Response
    {
        $this->cat_model->UpdateCat($_POST['id'], 'title', $_POST['title']);

        return $this->Responded(['code' => 1, 'title' => 'Catégorie modifier', 'message' => 'La catégories à bien été modifier']);
    }

    /**
     * @return Response
     */
    public function UpdateContent():Response
    {
        $this->cat_model->UpdateCat($_POST['id'], 'description', $_POST['content']);

        return $this->Responded(['code' => 1, 'title' => 'Catégorie modifier', 'message' => 'La catégories à bien été modifier']);
    }

    /**
     * @return Response
     */
    public function Add():Response
    {
        $this->cat_model->AddCat($_POST['title'], $_POST['content'], $_POST['slug'], $_POST['icon']);

        return $this->Responded(['code' => 1, 'title' => "Ajout d'une catégorie", 'message' => 'La catégories à bien été ajouter, rechargemen tde la page']);
    }
}
