<?php namespace App\Controllers\Blog;

/**
 * Class Errors
 *
 * @package App\Controllers\Blog
 */
class Errors extends Application
{

    /**
     * Errors constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Erreurs';
    }

    /**
     * @return string
     */
    public function index(): self
    {
        return $this->render('errors/home');
    }

    /**
     * @return string
     */
    public function show_404(): self
    {
        return $this->render('errors/404', 'Page introuvable');
    }
}
