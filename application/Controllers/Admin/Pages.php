<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

use App\Models\Admin\PagesModel;

/**
 * Class Pages
 *
 * @package App\Controllers\Admin
 */
class Pages extends Application
{
    /**
     * @var \App\Models\Admin\PagesModel
     */
    private $pages_model;

    /**
     * Pages constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->pages_model = new PagesModel();
        $this->stitle = 'Pages';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Pages|string
     */
    public function index(): self
    {
        $this->tpage = 'Liste des pages';
        $this->data['get_page'] = $this->pages_model->GetPages(false);

        return $this->render('pages/home');
    }

    /**
     * @param string $slug
     *
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Pages|string
     */
    public function edit(string $slug): self
    {
        $this->tpage = 'Modification de la page';
        $this->data['page_info'] = $this->pages_model->GetPage($slug);

        return $this->render('pages/edit');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Pages|string
     */
    public function add(): self
    {
        $this->tpage = 'Ajout d\'une page';

        return $this->render('pages/add');
    }
}
