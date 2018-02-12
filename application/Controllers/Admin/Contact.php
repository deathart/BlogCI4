<?php namespace App\Controllers\Admin;

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
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->contact_model = new ContactModel();
        $this->stitle = 'Contact';
    }

    /**
     * @return \App\Controllers\Admin\Contact|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        $this->tpage = 'Liste des contacts';

        return $this->render('contact/home');
    }

    /**
     * @return \App\Controllers\Admin\Contact|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function new(): self
    {
        $this->tpage = 'Liste des contacts nouveau';
        $this->data['get_list'] = $this->contact_model->getList(0);

        return $this->render('contact/new');
    }

    /**
     * @return \App\Controllers\Admin\Contact|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
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
     * @return \App\Controllers\Admin\Contact|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function rep(int $id): self
    {
        $this->tpage = 'Envoyez une réponse';
        $this->data['getContact'] = $this->contact_model->getContact($id);

        return $this->render('contact/rep');
    }
}
