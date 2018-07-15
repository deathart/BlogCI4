<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin;

use App\Models\Admin\AuthModel;
use CodeIgniter\HTTP\Response;
use Config\App;
use Config\Services;

/**
 * Class Auth
 *
 * @package App\Controllers\Admin
 */
class Auth extends Application
{
    /**
     * @var \App\Models\Admin\AuthModel
     */
    protected $auth_model;
    /**
     * @var array
     */
    protected $helpers = ['cookie'];

    /**
     * Auth constructor.
     *
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        parent::__construct();
        $config             = new App();
        $this->session      = Services::session($config);
        $this->request      = Services::request();
        $this->response     = new Response($config);
        $this->auth_model   = new AuthModel();
        $this->stitle       = 'Auth';
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return Application|Auth
     */
    public function index()
    {
        return $this->render('home');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Auth|string
     */
    public function Login(): self
    {
        return $this->render('auth/login', 'Connexion');
    }

    /**
     * @throws \Codeigniter\Files\Exceptions\FileNotFoundException
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @return \App\Controllers\Admin\Auth|string
     */
    public function Logout(): self
    {
        $this->session->destroy();
        delete_cookie('remember_me');
        delete_cookie('relog');

        return $this->render('auth/logout', 'Déconnexion');
    }

    /**
     *
     * @throws \InvalidArgumentException
     */
    public function login_ajax()
    {
        $this->response->setStatusCode(Response::HTTP_OK);
        $this->response->setHeader('Content-type', 'application/json');
        $this->response->noCache();
        $this->response->setHeader('X-robots-tag', 'noindex');
        $this->response->setHeader('X-XSS-Protection', '1; mode=block');
        $this->response->setHeader('X-Frame-Options', 'DENY');
        $this->response->setHeader('X-Content-Type-Options', 'nosniff');

        header('Content-type: application/json');

        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->auth_model->isValidEmail($_POST['email'])) {
                $account_id = $this->auth_model->isValidEmail($_POST['email']);
                $account_name = $this->auth_model->GetUsername($account_id);
                if ($this->auth_model->isValidPassword($_POST['password'], $account_id)) {
                    if ($_POST['remember'] == 1) {
                        $DataCookie = ['name' => 'remember_me', 'value' => $account_id, 'expire' => '32140800'];
                        set_cookie($DataCookie, true);
                        $DataCookieRelog = ['name' => 'relog', 'value' => true, 'expire' => '32140800'];
                        set_cookie($DataCookieRelog, true);
                    }
                    $this->session->set('Account_ip', $this->request->getIPAddress());
                    $this->session->set('Account_id', $account_id);
                    $this->session->set('Account_name', $account_name);
                    $this->session->set('logged_in', true);

                    $result = 1;
                } else {
                    $result = 0;
                }
            } else {
                $result = 0;
            }
        } else {
            $result = 0;
        }

        echo json_encode(['result' => $result]);

        exit();
    }
}
