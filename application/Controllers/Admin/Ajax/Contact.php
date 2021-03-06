<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin\Ajax;

use App\Libraries\Mailer;
use App\Models\Admin\ContactModel;
use CodeIgniter\HTTP\Response;

/**
 * Class Contact
 *
 * @package App\Controllers\Admin\Ajax
 */
class Contact extends Ajax
{
    /**
     * @var \App\Models\Admin\ContactModel
     */
    protected $contact_model;

    /**
     * @var \App\Libraries\Mailer
     */
    protected $mailer;

    /**
     * contact constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $this->contact_model = new ContactModel();
        $this->mailer = new Mailer();
    }

    /**
     * @return Response
     */
    public function rep():Response
    {
        $this->contact_model->markedview($_POST['id']);
        $infocontact = $this->contact_model->getContact($_POST['id']);
        if ($this->mailer->sendmail($_POST['sujet'], $infocontact->email, $_POST['message']) === true) {
            return $this->Responded(['code' => 1, 'title' => 'Contact', 'message' => 'Le message à bien été envoyé']);
        }

        return $this->Responded(['code' => 2, 'title' => 'Contact', 'message' => 'Message non envoyé']);
    }

    /**
     * @return Response
     */
    public function markedview():Response
    {
        $this->contact_model->markedview($_POST['id']);

        return $this->Responded(['code' => 1, 'title' => 'Contact', 'message' => 'La prise de contact à été marqué comme vue']);
    }

    /**
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @return Response
     */
    public function del():Response
    {
        $this->contact_model->del($_POST['id']);

        return $this->Responded(['code' => 1, 'title' => 'Contact', 'message' => 'La prise de contact à été supprimé']);
    }
}
