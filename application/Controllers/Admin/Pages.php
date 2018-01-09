<?php namespace App\Controllers\Admin;

use App\Models\Admin\PagesModel;

/**
 * Class Pages
 *
 * @package App\Controllers\Admin
 */
class Pages extends Application
{

    /**
     * @var \App\Models\Admin\PagesModel
     */
    private $pages_model;

    /**
     * Pages constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->pages_model = new PagesModel();
        $this->stitle = 'Pages';
    }

    /**
     * @return \App\Controllers\Admin\Pages|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        $this->tpage = 'Liste des pages';
        $this->data['get_page'] = $this->pages_model->GetPages(false);

        return $this->render('pages/home');
    }

    /**
     * @param string $slug
     *
     * @return \App\Controllers\Admin\Pages|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function edit(string $slug): self
    {
        $this->tpage = 'Modification de la page';
        $this->data['page_info'] = $this->pages_model->GetPage($slug);

        return $this->render('pages/edit');
    }

    /**
     * @return \App\Controllers\Admin\Pages|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function add(): self
    {
        $this->tpage = 'Ajout d\'une page';

        return $this->render('pages/add');
    }
}
