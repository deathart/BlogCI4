<?php namespace App\Libraries;

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
     * Getting a token
     *
     * @return string Return result
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
     * Checking the token
     *
     * @param string $token The token to check
     *
     * @return boolean Return result
     */
    public function validateToken($token)
    {
        if ($this->session->has($this->session_name)) {
            if ($this->session->get($this->session_name) === $token) {
                return true;
            }

            return '1';
        }

        return '0';
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
        $size   = strlen($chars) - 1;
        $string = '';
        while ($max--) {
            $string .= $chars[random_int(0, $size)];
        }

        return $string;
    }
}
