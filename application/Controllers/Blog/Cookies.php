<?php namespace App\Controllers\Blog;

/**
 * Class Cookies
 *
 * @package App\Controllers\Blog
 */
class Cookies extends Application
{
    /**
     * Cookies constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Cookies';
    }

    /**
     * @return \App\Controllers\Blog\Cookies|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        return $this->render('cookies/home');
    }
}
