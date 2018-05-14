<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog;

use App\Models\Blog\SearchModel;

/**
 * Class Search
 *
 * @package App\Controllers\Blog
 */
class Search extends Application
{
    /**
     * @var \App\Models\Blog\SearchModel
     */
    protected $search_model;

    /**
     * Search constructor.
     *
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Search';
        $this->search_model = new SearchModel();
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Blog\Search|string
     */
    public function index(): self
    {
        if (isset($_POST['mc_key'])) {
            $valeur = $_POST['mc_key'];

            $this->data['search_article'] = $this->search_model->bytype($valeur, 1);
            $this->data['search_com'] = $this->search_model->bytype($valeur, 2);
        } else {
            $valeur = '';
        }

        $this->data['val_mc'] = $valeur;

        return $this->render('search/home');
    }
}
