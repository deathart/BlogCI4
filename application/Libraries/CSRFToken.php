<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Libraries;

use Config\App;
use Config\Services;

/**
 * Class CSRFToken
 *
 * @package App\Libraries
 */
class CSRFToken
{
    /**
     * Length of the random string
     *
     * @var integer
     */
    private $length_random_string = 25;
    /**
     * Name of the session
     *
     * @var string
     */
    private $session_name;
    /**
     * @var \CodeIgniter\Session\Session
     */
    private $session;

    /**
     * Constructor
     *
     * @param string $session_name Name of the session (default: 'csrf_token')
     */
    public function __construct($session_name = 'csrf_token')
    {
        $this->session      = Services::session(new App());
        $this->session_name = $session_name;
    }

    /**
     * @return array|string
     */
    public function getToken()
    {
        if ($this->session->has($this->session_name)) {
            return $this->session->get($this->session_name);
        }

        $token = uniqid($this->randomString(), true);
        $this->session->set($this->session_name, $token);

        return $token;
    }

    /**
     * @param $token
     *
     * @return bool
     */
    public function validateToken($token): bool
    {
        if ($this->session->has($this->session_name)) {
            if ($this->session->get($this->session_name) === $token) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Generate a random string
     *
     * @return string Return result
     */
    private function randomString(): string
    {
        $chars  = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP';
        $max    = $this->length_random_string;
        $size   = \strlen($chars) - 1;
        $string = '';
        while ($max--) {
            $string .= $chars[random_int(0, $size)];
        }

        return $string;
    }
}
