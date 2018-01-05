<?php namespace App\Controllers\Admin\Ajax;

use App\Libraries\Twig\Twig;
use App\Models\Admin\MediaModel;

/**
 * Class Media
 *
 * @package App\Controllers\Admin\Ajax
 */
class Media extends Ajax
{

    /**
     * @var \App\Models\Admin\MediaModel
     */
    private $media_model;

    /**
     * @var \App\Libraries\Twig\Twig
     */
    protected $twig;

    /**
     * Media constructor.
     *
     * @param array ...$params
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->media_model = new MediaModel();
        $this->twig = new Twig('admin');
    }

    /**
     *
     */
    public function modal()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $data_ajax = [
                    'get_all_media' => $this->media_model->Get_All(),
                    'type_modal' => $_POST['type_modal']
                ];

                return $this->responded(['code' => 1, 'title' => 'Media', 'content' => $this->twig->Rendered('media/modal', $data_ajax)]);
            }

            return $this->responded(['code' => 0, 'message' => 'Erreurs...']);
        }

        return $this->responded([]);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function add_media()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                if ($img = $this->request->getFile('pictures')) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $name = $img->getName();
                        $path = 'uploads/' . date('Y') . '/' . date('n');
                        if (!$this->media_model->isAlreadyExists($img->getName())) {
                            if ($img->move(FCPATH . $path, $name, true)) {
                                $id_pic = $this->media_model->Add('uploads/' . date('Y') . '/' . date('n') . '/', $img->getName());

                                return $this->responded(['code' => 1, 'title' => 'Medias', 'message' => 'Image importée avec success', 'id' => $id_pic, 'slug' => $path  . '/' . $img->getName()]);
                            }

                            throw $this->responded(['code' => 0, 'message' => 'Erreur : ' .$img->getErrorString().'('.$img->getError().')']);
                        }

                        return $this->responded(['code' => 0, 'message' => 'Erreur : L\'image existe déjà']);
                    }

                    throw $this->responded(['code' => 0, 'message' => 'Erreur : ' .$img->getErrorString().'('.$img->getError().')']);
                }
            }

            return $this->responded(['code' => 0]);
        }

        return $this->responded(['code' => 0]);
    }

    /**
     *
     */
    public function remove_media()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                if (file_exists(FCPATH . $_POST['slug'])) {
                    unlink(FCPATH . $_POST['slug']);
                }

                if ($this->media_model->isAlreadyExists($_POST['name'])) {
                    $this->media_model->removeMedia($_POST['id']);
                }

                return $this->responded(['code' => 1, 'title' => 'Medias', 'message' => 'Image supprimé']);
            }

            return $this->responded(['code' => 0]);
        }

        return $this->responded(['code' => 0]);
    }
}
