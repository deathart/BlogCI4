<?php namespace App\Controllers\Admin;

use App\Models\Admin\CatModel;

class Cat extends Application
{

    /**
     * @var \App\Models\Admin\CatModel
     */
    private $cat_model;

    /**
     * Article constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->cat_model = new CatModel();
        $this->stitle = 'Catgories';
    }

    public function index(): self
    {
        $this->tpage = 'Liste des categories';
        $this->data['get_cat'] = $this->cat_model->getlist();
        return $this->render('cat/list');
    }
}
