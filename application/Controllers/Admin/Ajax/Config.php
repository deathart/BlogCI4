<?php namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\ConfigModel;

/**
 * Class Config
 *
 * @package App\Controllers\Admin\Ajax
 */
class Config extends Ajax
{

    /**
     * @var \App\Models\Admin\ConfigModel
     */
    protected $config_model;

    /**
     * Config constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->config_model = new ConfigModel();
    }

    /**
     * @return \CodeIgniter\HTTP\Response
     */
    public function updateparams()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->config_model->UpdateConfig($_POST['id'], $_POST['key'], $_POST['data']);

                return $this->responded(['code' => 1, 'title' => 'Paramètres', 'message' => 'Les paramêtres on bien été mise à jours']);
            }
        } else {
            return $this->responded([]);
        }
    }

    /**
     * @return \CodeIgniter\HTTP\Response
     */
    public function delparams()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->config_model->DelConfig($_POST['id']);

                return $this->responded(['code' => 1, 'title' => 'Paramètres', 'message' => 'Paramêtre supprimé avec success']);
            }
        } else {
            return $this->responded([]);
        }
    }
}
