<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

use App\Models\Admin\ContactModel;

/**
 * Class Contact
 *
 * @package App\Controllers\Admin
 */
class Contact extends Application
{
    /**
     * @var \App\Models\Admin\ContactModel
     */
    private $contact_model;

    /**
     * Article constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->contact_model = new ContactModel();
        $this->stitle = 'Contact';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Contact|string
     */
    public function index(): self
    {
        $this->tpage = 'Liste des contacts';

        return $this->render('contact/home');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Contact|string
     */
    public function new(): self
    {
        $this->tpage = 'Liste des contacts nouveau';
        $this->data['get_list'] = $this->contact_model->getList(0);

        return $this->render('contact/new');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Contact|string
     */
    public function finish(): self
    {
        $this->tpage = 'Liste des contacts fini';
        $this->data['get_list'] = $this->contact_model->getList(1);

        return $this->render('contact/finish');
    }

    /**
     * @param int $id
     *
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Contact|string
     */
    public function rep(int $id): self
    {
        $this->tpage = 'Envoyez une réponse';
        $this->data['getContact'] = $this->contact_model->getContact($id);

        return $this->render('contact/rep');
    }
}
