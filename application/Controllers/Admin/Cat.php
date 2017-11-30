<?php namespace App\Controllers\Admin;

use App\Models\Admin\CatModel;

/**
 * Class Cat
 *
 * @package App\Controllers\Admin
 */
class Cat extends Application
{

    /**
     * @var \App\Models\Admin\CatModel
     */
    private $cat_model;

    /**
     * Cat constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->cat_model = new CatModel();
        $this->stitle = 'Categories';
    }

    /**
     * @return \App\Controllers\Admin\Cat
     */
    public function index(): self
    {
        $this->tpage = 'Liste des categories';
        $this->data['get_cat'] = $this->cat_model->getlist();
        return $this->render('cat/list');
    }
}
