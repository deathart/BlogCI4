<?php namespace App\Controllers\Admin\Ajax;

use App\Models\Admin\PagesModel;

class Pages extends Ajax
{
    protected $data = [];

    /**
     * @var \App\Models\Admin\PagesModel
     */
    private $pages_model;

    public function __construct(...$params)
    {
        parent::__construct(...$params);
        $this->pages_model = new PagesModel();
    }

    public function edit()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                if ($this->request->isValidIP($this->request->getIPAddress())) {
                    $this->pages_model->Edit($_POST['pageid'], $_POST['title'], $_POST['link'], $_POST['content'], $_POST['active']);
                    return $this->responded(['code' => 1, 'title' => 'Pages', 'message' => 'La page à bien été modifiée']);
                } else {
                    return $this->responded(['code' => 0, 'message' => 'Error : your IP is bizzar ?']);
                }
            } else {
                return $this->responded(['code' => 0, 'message' => 'Error CSRF, You are HACKER ?']);
            }
        }
        return $this->responded([]);
    }

    public function add()
    {
        if ($this->isConnected()) {
            if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
                if ($this->request->isValidIP($this->request->getIPAddress())) {
                    $this->pages_model->Add($_POST['title'], $_POST['link'], $_POST['content'], $_POST['active']);
                    return $this->responded(['code' => 1, 'title' => 'Pages', 'message' => 'La page à bien été crée']);
                } else {
                    return $this->responded(['code' => 0, 'message' => 'Error : your IP is bizzar ?']);
                }
            } else {
                return $this->responded(['code' => 0, 'message' => 'Error CSRF, You are HACKER ?']);
            }
        }
        return $this->responded([]);
    }
}
