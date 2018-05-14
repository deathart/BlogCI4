<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Filters;

use App\Libraries\Auth;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\App;
use Config\Services;

/**
 * Class Admin
 *
 * @package App\Filters
 */
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
     * @param \CodeIgniter\HTTP\IncomingRequest|RequestInterface $request
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
     * @param \CodeIgniter\HTTP\IncomingRequest|RequestInterface $request
     * @param \CodeIgniter\HTTP\Response|ResponseInterface $response
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response)
    {
    }
}
