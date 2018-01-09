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
     * @return \App\Controllers\Blog\Errors|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        return $this->render('errors/home');
    }

    /**
     * @return \App\Controllers\Blog\Errors|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function show_404(): self
    {
        return $this->render('errors/404', 'Page introuvable');
    }
}
