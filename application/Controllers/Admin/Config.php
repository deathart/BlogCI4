<?php namespace App\Controllers\Admin;

use App\Models\Admin\ConfigModel;

class Config extends Application
{

    /**
     * Article constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Config';
    }

    public function index(): self
    {
        return $this->render('config/home');
    }

    public function params(): self
    {
        $this->tpage = 'Paramêtres';
        $this->data['getall'] = $this->config_model->GetConfigAll();
        return $this->render('config/params');
    }
}
