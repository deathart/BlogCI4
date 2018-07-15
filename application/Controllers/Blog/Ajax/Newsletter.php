<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog\Ajax;

use App\Models\Blog\NewsletterModel;
use CodeIgniter\HTTP\Response;

/**
 * Class Comments
 *
 * @package App\Controllers\Blog\Ajax
 */
class Newsletter extends Ajax
{
    /**
     * @var \App\Models\Blog\NewsletterModel
     */
    private $newsletter_model;

    /**
     * Contact constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->newsletter_model = new NewsletterModel();
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        if (!$this->newsletter_model->Check($this->request->getIPAddress(), $_POST['email'])) {
            $this->newsletter_model->Add($this->request->getIPAddress(), $_POST['email']);

            return $this->Render(['message' => 'Success : Votre email à bien été enregistré', 'code' => 1]);
        }

        return $this->Render(['message' => 'Erreur : Votre email à déjà été enregistré', 'code' => 2]);
    }
}
