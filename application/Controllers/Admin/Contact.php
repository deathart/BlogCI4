<?php namespace App\Controllers\Admin;

use App\Models\Admin\ContactModel;

class Contact extends Application
{

    /**
     * @var \App\Models\Admin\ContactModel
     */
    private $contact_model;

    /**
     * Article constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->contact_model = new ContactModel();
        $this->stitle = 'Contact';
    }

    public function index(): self
    {
        $this->tpage = 'Liste des contacts';
        return $this->render('contact/home');
    }

    public function new(): self
    {
        $this->tpage = 'Liste des contacts nouveau';
        $this->data['get_list'] = $this->contact_model->getList(0);
        return $this->render('contact/new');
    }

    public function finish(): self
    {
        $this->tpage = 'Liste des contacts fini';
        $this->data['get_list'] = $this->contact_model->getList(1);
        return $this->render('contact/finish');
    }

    public function rep(int $id): self
    {
        $this->tpage = 'Envoyez une réponse';
        $this->data['getContact'] = $this->contact_model->getContact($id);
        return $this->render('contact/rep');
    }
}
