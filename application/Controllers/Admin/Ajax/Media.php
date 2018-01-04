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

    protected $twig;

    /**
     * Media constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->media_model = new MediaModel();
        $this->twig = new Twig('admin');
    }

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
}
