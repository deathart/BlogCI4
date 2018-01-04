<?php namespace App\Controllers\Admin;

/**
 * Class Config
 *
 * @package App\Controllers\Admin
 */
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

    /**
     * @return \App\Controllers\Admin\Config
     */
    public function index(): self
    {
        return $this->render('config/home');
    }

    /**
     * @return \App\Controllers\Admin\Config
     */
    public function params(): self
    {
        $this->tpage = 'Paramêtres';
        $this->data['getall'] = $this->config_model->GetConfigAll();

        return $this->render('config/params');
    }
}
