<?php namespace App\Controllers\Admin;

/**
 * Class Home
 *
 * @package App\Controllers\Admin
 */
class Home extends Application
{

    /**
     * Home constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Accueil';
    }

    /**
     * @return string
     */
    public function index(): self
    {
        return $this->render('home');
    }
}
