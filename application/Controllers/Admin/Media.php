<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

use App\Models\Admin\MediaModel;

/**
 * Class Media
 *
 * @package App\Controllers\Admin
 */
class Media extends Application
{
    /**
     * @var \App\Models\Blog\MediaModel
     */
    private $media_model;

    /**
     * Media constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        parent::__construct();
        $this->media_model = new MediaModel();
        $this->stitle = 'Media';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Media|string
     */
    public function index(): self
    {
        $this->tpage = 'Liste des medias';
        $this->data['get_all_media'] = $this->media_model->Get_All();

        return $this->render('media/home');
    }
}
