<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog\Ajax;

use App\Models\Blog\ContactModel;
use CodeIgniter\HTTP\Response;

/**
 * Class Contact
 *
 * @package App\Controllers\Blog\Ajax
 */
class Contact extends Ajax
{
    /**
     * @var \App\Models\Blog\ContactModel
     */
    private $contact_model;

    /**
     * Contact constructor.
     *
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->contact_model = new ContactModel();
    }

    /**
     * @return Response
     */
    public function addContact(): Response
    {
        $this->contact_model->Add($_POST['name'], $_POST['email'], $_POST['sujet'], $_POST['message'], $this->request->getIPAddress());

        return $this->Render(['message' => "La prise de contact à bien été envoyez, vous receverez une réponse d'ici 24h à 48h", 'code' => 1]);
    }
}
