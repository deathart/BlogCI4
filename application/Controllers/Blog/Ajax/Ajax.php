<?php namespace App\Controllers\Blog\Ajax;

use App\Libraries\CSRFToken;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\Response;
use Config\App;
use Config\Services;

/**
 * Class Ajax
 *
 * @package App\Controllers\Blog\Ajax
 */
class Ajax extends Controller
{

    /**
     * @var array
     */
    protected $helpers = ['cookie'];
    /**
     * @var \App\Libraries\CSRFToken
     */
    protected $csrf;

    /**
     * Ajax constructor.
     *
     * @param array ...$params
     */
    public function __construct(...$params)
    {
        parent::__construct(...$params);

        $config  = new App();
        $session = Services::session($config);
        $session->start();

        $this->csrf = new CSRFToken();

        $this->response->setStatusCode(Response::HTTP_OK);
        $this->response->setHeader('Content-type', 'application/json');
        $this->response->noCache();
        $this->response->setHeader('X-robots-tag', 'noindex');
        $this->response->setHeader('X-XSS-Protection', '1; mode=block');
        $this->response->setHeader('X-Frame-Options', 'DENY');
        $this->response->setHeader('X-Content-Type-Options', 'nosniff');

        header('Content-type: application/json');
    }

    /**
     *
     */

    /**
     * @param array $data
     * @param bool $error
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function Render(array $data, bool $error = false)
    {
        if (!$error) {
            return $this->response->setStatusCode(Response::HTTP_OK)->setContentType('application/json')->setBody(json_encode($data))->send();
        }

        return $this->response->setStatusCode(500)->setContentType('application/json')->setBody(json_encode($data))->send();

        exit();
    }
}
