<?php namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\CatModel;

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
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->cat_model = new CatModel();
    }

    public function UpdateTitle()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->cat_model->UpdateCat($_POST['id'], 'title', $_POST['title']);

                return $this->responded(['code' => 1, 'title' => 'Catégorie modifier', 'message' => 'La catégories à bien été modifier']);
            }

            return $this->responded(['code' => 0, 'message' => 'Erreurs...']);
        }

        return $this->responded([]);
    }

    public function UpdateContent()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->cat_model->UpdateCat($_POST['id'], 'description', $_POST['content']);

                return $this->responded(['code' => 1, 'title' => 'Catégorie modifier', 'message' => 'La catégories à bien été modifier']);
            }

            return $this->responded(['code' => 0, 'message' => 'Erreurs...']);
        }

        return $this->responded([]);
    }

    public function Add()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->cat_model->AddCat($_POST['title'], $_POST['content'], $_POST['slug'], $_POST['icon']);

                return $this->responded(['code' => 1, 'title' => "Ajout d'une catégorie", 'message' => 'La catégories à bien été ajouter, rechargemen tde la page']);
            }

            return $this->responded(['code' => 0, 'message' => 'Erreurs...']);
        }

        return $this->responded([]);
    }
}
