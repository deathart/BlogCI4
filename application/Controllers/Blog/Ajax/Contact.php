<?php namespace App\Controllers\Blog\Ajax;

use App\Models\Blog\ContactModel;

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
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->contact_model = new ContactModel();
    }

    /**
     * @return bool
     */
    public function addContact()
    {
        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->request->isValidIP($this->request->getIPAddress())) {
                $this->contact_model->Add($_POST['name'], $_POST['email'], $_POST['sujet'], $_POST['message'], $this->request->getIPAddress());
                $this->Render(['message' => "La prise de contact à bien été envoyez, vous receverez une réponse d'ici 24h à 48h", 'code' => 1]);

                return true;
            }
            $this->Render(['message' => 'Error : yout IP is bizzar ?', 'code' => 2]);

            return false;
        }
        $this->Render(['message' => 'Error CSRF, You are HACKER ?', 'code' => 2]);

        return false;
    }
}
