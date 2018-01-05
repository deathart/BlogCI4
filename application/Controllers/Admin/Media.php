<?php namespace App\Controllers\Admin;

use App\Models\Admin\MediaModel;

/**
 * Class Media
 *
 * @package App\Controllers\Admin
 */
class Media extends Application
{

    /**
     * @var \App\Models\Blog\MediaModel
     */
    private $media_model;

    /**
     * Media constructor.
     *
     * @param array ...$params
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     * @throws \InvalidArgumentException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->media_model = new MediaModel();
        $this->stitle = 'Media';
    }

    /**
     * @return \App\Controllers\Admin\Media|string
     * @throws \Codeigniter\UnknownFileException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): self
    {
        $this->tpage = 'Liste des medias';
        $this->data['get_all_media'] = $this->media_model->Get_All();

        return $this->render('media/home');
    }
}
