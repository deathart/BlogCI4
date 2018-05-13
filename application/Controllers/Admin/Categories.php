<?php namespace App\Controllers\Admin;

use App\Models\Admin\CategoriesModel;

/**
 * Class Categories
 *
 * @package App\Controllers\Admin
 */
class Categories extends Application
{

    /**
     * @var \App\Models\Admin\CategoriesModel
     */
    private $categories_model;

    /**
     * Categories constructor.
     *
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->categories_model = new CategoriesModel();
        $this->stitle = 'Categories';
    }

    /**
     * @return \App\Controllers\Admin\Categories|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        $this->tpage = 'Liste des categories';
        $this->data['get_cat'] = $this->categories_model->getlist();

        return $this->render('categories/list');
    }
}
