<?php namespace App\Controllers\Admin\Ajax;

use App\Libraries\Mailer;
use App\Models\Admin\ContactModel;

class Contact extends Ajax
{
    protected $contact_model;

    protected $mailer;

    /**
     * contact constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->contact_model = new ContactModel();
        $this->mailer = new Mailer();
    }

    public function rep()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->contact_model->markedview($_POST['id']);
                $infocontact = $this->contact_model->getContact($_POST['id']);
                $this->mailer->sendmail($_POST['sujet'], $infocontact->email, $_POST['message']);

                return $this->responded(['code' => 1, 'title' => 'Contact', 'message' => 'Le message à bien été envoyé']);
            }
        } else {
            return $this->responded([]);
        }
    }

    public function markedview()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->contact_model->markedview($_POST['id']);

                return $this->responded(['code' => 1, 'title' => 'Contact', 'message' => 'La prise de contact à été marqué comme vue']);
            }
        } else {
            return $this->responded([]);
        }
    }

    public function del()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->contact_model->del($_POST['id']);

                return $this->responded(['code' => 1, 'title' => 'Contact', 'message' => 'La prise de contact à été supprimé']);
            }
        } else {
            return $this->responded([]);
        }
    }
}
