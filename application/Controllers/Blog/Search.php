<?php namespace App\Controllers\Blog;

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
     * @return \App\Controllers\Blog\Search|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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
