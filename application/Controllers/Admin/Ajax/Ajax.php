<?php namespace App\Controllers\Admin\Ajax;

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
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    protected $request;
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
        $this->config        = new App();
        $this->session      = Services::session($this->config);
        $this->request      = Services::request();
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
            } else {
                return false;
            }
        } else {
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
    }

    /**
     * @param array $arr
     * @param int $code
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function responded(array $arr, int $code = 200)
    {
        $this->response = new Response($this->config);

        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->isConnected()) {
                $this->response->setStatusCode($code)->setContentType('application/json')->setBody(json_encode($arr))->send();
            } else {
                $this->response->setStatusCode(403)->setContentType('application/json')->setBody(json_encode(['error' => 'You are not loged', 'error_code' => 4001]))->send();
            }
        } else {
            $this->response->setStatusCode(403)->setContentType('application/json')->setBody(json_encode(['error' => 'Error CSRF, You are HACKER ?', 'error_code' => 4010]))->send();
            return false;
        }
        exit();
    }
}
