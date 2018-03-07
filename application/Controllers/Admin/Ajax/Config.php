<?php namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\ConfigModel;
use CodeIgniter\HTTP\Response;

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
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->config_model = new ConfigModel();
    }

    /**
     * @return Response
     */
    public function updateparams():Response
    {
        $this->config_model->UpdateConfig($_POST['id'], $_POST['key'], $_POST['data']);

        return $this->Responded(['code' => 1, 'title' => 'Paramètres', 'message' => 'Les paramêtres on bien été mise à jours']);
    }

    /**
     * @return Response
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function delparams():Response
    {
        $this->config_model->DelConfig($_POST['id']);

        return $this->Responded(['code' => 1, 'title' => 'Paramètres', 'message' => 'Paramêtre supprimé avec success']);
    }
}
