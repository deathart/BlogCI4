<?php namespace App\Controllers\Blog;

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
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Contact';
        $this->contact_model = new ContactModel();
    }

    /**
     * @return string
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
