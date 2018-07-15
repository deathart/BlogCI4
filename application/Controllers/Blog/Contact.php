<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog;

use App\Models\Blog\ContactModel;

/**
 * Class Contact
 *
 * @package App\Controllers\Blog
 */
class Contact extends Application
{
    /**
     * @var \App\Models\Blog\ContactModel
     */
    protected $contact_model;

    /**
     * Contact constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->stitle = 'Contact';
        $this->contact_model = new ContactModel();
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Blog\Contact|string
     */
    public function index(): self
    {
        if ($this->config_model->GetConfig('contact_active') == 0) {
            return redirect(base_url('errors/404'));
        }
        $this->data['check'] = $this->contact_model->Check($this->request->getIPAddress());

        return $this->render('contact/home');
    }
}
