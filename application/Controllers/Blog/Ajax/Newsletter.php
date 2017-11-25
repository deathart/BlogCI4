<?php namespace App\Controllers\Blog\Ajax;

use App\Models\Blog\NewsletterModel;

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
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->newsletter_model = new NewsletterModel();
    }

    /**
     * @return bool
     */
    public function index()
    {
        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->request->isValidIP($this->request->getIPAddress())) {
                if (!$this->newsletter_model->Check($this->request->getIPAddress(), $_POST['email'])) {
                    $this->newsletter_model->Add($this->request->getIPAddress(), $_POST['email']);
                    $this->Render(['message' => 'Success : Votre email à bien été enregistré', 'code' => 1]);
                    return true;
                } else {
                    $this->Render(['message' => 'Erreur : Votre email à déjà été enregistré', 'code' => 2]);
                    return false;
                }
            } else {
                $this->Render(['message' => 'Error : yout IP is bizzar ?', 'code' => 2]);
                return false;
            }
        } else {
            $this->Render(['message' => 'Error CSRF, You are HACKER ?', 'code' => 2]);
            return false;
        }
    }
}
