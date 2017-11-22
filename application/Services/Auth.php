<?php namespace App\Services;

use Config\App;
use Config\Services;

class Auth
{
    /**
     * @var \CodeIgniter\Session\Session
     */
    protected $session;
    /**
     * @var \CodeIgniter\HTTP\IncomingRequest
     */
    protected $request;

    public function __construct()
    {
        $config        = new App();
        $this->session = Services::session($config);
        $this->request = Services::request();
        helper('cookie');
    }

    public function isConnected()
    {
        if (uri_string() != 'admin/auth/login' && uri_string() != 'admin/auth/login_ajax') {
            if (!$this->session->has('logged_in')) {
                if (get_cookie('remember_me', true) != null) {
                    $this->session->set('Account_ip', $this->request->getIPAddress());
                    $this->session->set('Account_id', get_cookie('remember_me', true));
                    $this->session->set('logged_in', true);
                    delete_cookie('remember_me');
                    set_cookie(['name' => 'remember_me', 'value' => get_cookie('remember_me', true), 'expire' => '32140800'], true);

                    return redirect($_SERVER['REQUEST_URI']);
                }

                return false;
            } else {
                if (get_cookie('remember_me', true) != null) {
                    $this->session->set('Account_ip', $this->request->getIPAddress());
                    $this->session->set('Account_id', get_cookie('remember_me', true));
                    $this->session->set('logged_in', true);
                    delete_cookie('remember_me');
                    set_cookie(['name' => 'remember_me', 'value' => get_cookie('remember_me', true), 'expire' => '32140800'], true);
                }

                return true;
            }
        } else {
            return true;
        }
    }
}
