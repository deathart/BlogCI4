<?php namespace App\Controllers\Blog;

use App\Models\Blog\PagesModel;

/**
 * Class Pages
 *
 * @package App\Controllers\Blog
 */
class Pages extends Application
{

    /**
     * @var \App\Models\Blog\PagesModel
     */
    protected $pages_model;

    /**
     * Pages constructor.
     *
     * @param array ...$params
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->stitle = 'Pages';
        $this->pages_model = new PagesModel();
    }

    /**
     * @return \App\Controllers\Blog\Pages|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        return $this->render('pages/home');
    }

    /**
     * @param string $slug
     *
     * @return \App\Controllers\Blog\Pages|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function view(string $slug): self
    {
        $this->data['page_info'] = $this->pages_model->GetPage($slug);

        return $this->render('pages/view');
    }
}
