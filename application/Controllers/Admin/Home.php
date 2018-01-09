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
     * @return \App\Controllers\Admin\Home|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        return $this->render('home');
    }
}
