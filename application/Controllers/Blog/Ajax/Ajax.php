<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Controllers\Blog\Ajax;

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
     */
    public function __construct()
    {
        $config  = new App();
        $session = Services::session($config);
        $session->start();

        $this->csrf = new CSRFToken();
    }

    /**
     *
     */

    /**
     * @param array $data
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function Render(array $data): Response
    {
        if ($this->csrf->validateToken($_SERVER['HTTP_X_CSRFTOKEN'])) {
            if ($this->request->isValidIP($this->request->getIPAddress())) {
                return $this->response
                    ->setStatusCode(Response::HTTP_OK)
                    ->setContentType('application/json')
                    ->setHeader('X-robots-tag', 'noindex')
                    ->setHeader('X-XSS-Protection', '1; mode=block')
                    ->setHeader('X-Frame-Options', 'DENY')
                    ->setHeader('X-Content-Type-Options', 'nosniff')
                    ->setBody(json_encode($data))
                    ->noCache();
            }

            return $this->response
                ->setStatusCode(Response::HTTP_FORBIDDEN)
                ->setContentType('application/json')
                ->setHeader('X-robots-tag', 'noindex')
                ->setHeader('X-XSS-Protection', '1; mode=block')
                ->setHeader('X-Frame-Options', 'DENY')
                ->setHeader('X-Content-Type-Options', 'nosniff')
                ->setBody(json_encode(['message' => 'Error : yout IP is bizzar ?', 'code' => 2]))
                ->noCache();
        }

        return $this->response
            ->setStatusCode(Response::HTTP_FORBIDDEN)
            ->setContentType('application/json')
            ->setHeader('X-robots-tag', 'noindex')
            ->setHeader('X-XSS-Protection', '1; mode=block')
            ->setHeader('X-Frame-Options', 'DENY')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setBody(json_encode(['error' => 'Error CSRF, You are HACKER ?', 'error_code' => 2]))
            ->noCache();
    }
}
