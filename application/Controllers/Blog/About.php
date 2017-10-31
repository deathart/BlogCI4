<?php namespace App\Controllers\Blog;

/**
 * Class About
 *
 * @package App\Controllers\Blog
 */
class About extends Application
{

    /**
     * About constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Informations';
    }

    /**
     * @return string
     */
    public function index(): self
    {
        return $this->render('about/home');
    }
}
