<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Admin\Ajax;

use App\Libraries\CSRFToken;
use App\Libraries\General;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Response;
use Config\App;
use Config\Services;

/**
 * Class Ajax
 *
 * @package App\Controllers\Admin\Ajax
 */
class Ajax extends Controller
{
    /**
     * @var \Config\App
     */
    protected $config;
    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;
    /**
     * @var \App\Libraries\General
     */
    protected $General;
    /**
     * @var \App\Libraries\CSRFToken
     */
    protected $csrf;
    /**
     * @var array
     */
    protected $helpers = ['cookie'];

    /**
     * Ajax constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);

        //Declare class
        $this->config       = new App();
        $this->session      = Services::session($this->config);
        $this->General      = new General;
        $this->csrf         = new CSRFToken();
    }

    /**
     * @return bool
     */
    public function isConnected()
    {
        if (!$this->session->has('logged_in')) {
            if (get_cookie('remember_me', true) != null) {
                $this->session->set('Account_ip', $this->request->getIPAddress());
                $this->session->set('Account_id', get_cookie('remember_me', true));
                //$this->session->set ('Account_name', $this->general->Get_display_name(get_cookie("remember_me", TRUE)));
                $this->session->set('logged_in', true);
                delete_cookie('remember_me');
                set_cookie(['name' => 'remember_me', 'value' =>  get_cookie('remember_me', true), 'expire' => '32140800'], true);
                header('Location: ' . $_SERVER['REQUEST_URI']);
                exit();
            }

            return false;
        }
        if (get_cookie('remember_me', true) != null) {
            $this->session->set('Account_ip', $this->request->getIPAddress());
            $this->session->set('Account_id', get_cookie('remember_me', true));
            //$this->session->set ('Account_name', $this->general->Get_display_name(get_cookie("remember_me", TRUE)));
            $this->session->set('logged_in', true);
            delete_cookie('remember_me');
            set_cookie(['name' => 'remember_me', 'value' =>  get_cookie('remember_me', true), 'expire' => '32140800'], true);
        }

        return true;
    }

    /**
     * @param array $arr
     * @return Response
     */
    public function Responded(array $arr): Response
    {
        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->request->isValidIP($this->request->getIPAddress())) {
                if ($this->isConnected()) {
                    return $this->response
                        ->setStatusCode(Response::HTTP_OK)
                        ->setContentType('application/json')
                        ->setHeader('X-robots-tag', 'noindex')
                        ->setHeader('X-XSS-Protection', '1; mode=block')
                        ->setHeader('X-Frame-Options', 'DENY')
                        ->setHeader('X-Content-Type-Options', 'nosniff')
                        ->setBody(json_encode($arr))
                        ->noCache();
                }

                return $this->response
                    ->setStatusCode(Response::HTTP_FORBIDDEN)
                    ->setContentType('application/json')
                    ->setHeader('X-robots-tag', 'noindex')
                    ->setHeader('X-XSS-Protection', '1; mode=block')
                    ->setHeader('X-Frame-Options', 'DENY')
                    ->setHeader('X-Content-Type-Options', 'nosniff')
                    ->setBody(json_encode(['error' => 'You are not loged', 'error_code' => 4001]))
                    ->noCache();
            }

            return $this->response
                ->setStatusCode(Response::HTTP_FORBIDDEN)
                ->setContentType('application/json')
                ->setHeader('X-robots-tag', 'noindex')
                ->setHeader('X-XSS-Protection', '1; mode=block')
                ->setHeader('X-Frame-Options', 'DENY')
                ->setHeader('X-Content-Type-Options', 'nosniff')
                ->setBody(json_encode(['error' => 'Error : yout IP is bizzar ?', 'error_code' => 4010]))
                ->noCache();
        }

        return $this->response
            ->setStatusCode(Response::HTTP_FORBIDDEN)
            ->setContentType('application/json')
            ->setHeader('X-robots-tag', 'noindex')
            ->setHeader('X-XSS-Protection', '1; mode=block')
            ->setHeader('X-Frame-Options', 'DENY')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setBody(json_encode(['error' => 'Error CSRF, You are HACKER ?', 'error_code' => 4010]))
            ->noCache();
    }
}
