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
     * @return string
     */
    public function index(): self
    {
        return $this->render('cookies/home');
    }
}
