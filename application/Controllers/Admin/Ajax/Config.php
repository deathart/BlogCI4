<?php namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\ConfigModel;

class Config extends Ajax
{
    protected $configmodel;
    /**
     * Comments constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->config_model = new ConfigModel();
    }

    public function updateparams()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->config_model->UpdateConfig($_POST['id'], $_POST['key'], $_POST['data']);
                return $this->responded(["code" => 1, "title" => "Paramètres", "message" => "Les paramêtres on bien été mise à jours"]);
            }
        } else {
            return $this->responded([]);
        }
    }

    public function delparams()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                $this->config_model->DelConfig($_POST['id']);
                return $this->responded(["code" => 1, "title" => "Paramètres", "message" => "Paramêtre supprimé avec success"]);
            }
        } else {
            return $this->responded([]);
        }
    }
}
