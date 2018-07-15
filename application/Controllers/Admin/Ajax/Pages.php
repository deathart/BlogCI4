<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\PagesModel;

/**
 * Class Pages
 *
 * @package App\Controllers\Admin\Ajax
 */
class Pages extends Ajax
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
    }

    /**
     * @return Response
     */
    public function edit():Response
    {
        $this->pages_model->Edit($_POST['pageid'], $_POST['title'], $_POST['link'], $_POST['content'], $_POST['active']);

        return $this->Responded(['code' => 1, 'title' => 'Pages', 'message' => 'La page à bien été modifiée']);
    }

    /**
     * @return Response
     */
    public function add():Response
    {
        $this->pages_model->Add($_POST['title'], $_POST['link'], $_POST['content'], $_POST['active']);

        return $this->Responded(['code' => 1, 'title' => 'Pages', 'message' => 'La page à bien été crée']);
    }
}
