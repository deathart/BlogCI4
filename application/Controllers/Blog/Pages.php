<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog;

use App\Models\Blog\PagesModel;

/**
 * Class Pages
 *
 * @package App\Controllers\Blog
 */
class Pages extends Application
{
    /**
     * @var \App\Models\Blog\PagesModel
     */
    protected $pages_model;

    /**
     * Pages constructor.
     *
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Pages';
        $this->pages_model = new PagesModel();
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Blog\Pages|string
     */
    public function index(): self
    {
        return $this->render('pages/home');
    }

    /**
     * @param string $slug
     *
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Blog\Pages|string
     */
    public function view(string $slug): self
    {
        $this->data['page_info'] = $this->pages_model->GetPage($slug);

        return $this->render('pages/view');
    }
}
