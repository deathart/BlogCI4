<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\Auth;
use Config\App;
use Config\Services;

class Admin implements FilterInterface
{
    public function __construct()
    {
        $session = Services::session(new App());
        $session->start();
    }

    /**
     * We don't need to do anything here.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     *
     * @return mixed
     */
    public function before(RequestInterface $request)
    {
        $auth = new Auth();
        if (!$auth->isConnected()) {
            return redirect(base_url('admin/auth/login'));
        }
    }

    /**
     * If the debug flag is set (CI_DEBUG) then collect performance
     * and debug information and display it in a toolbar.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     * @param ResponseInterface|\CodeIgniter\HTTP\Response $response
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response)
    {
    }
}
