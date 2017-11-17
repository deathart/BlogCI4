<?php namespace App\Libraries;

use Config\App;
use Config\Services;

/**
 * Class Captcha
 *
 * @package App\Libraries
 */
class Captcha
{
    /**
     * @var \Config\App
     */
    private $config;

    /**
     * @var \CodeIgniter\Session\Session
     */
    private $session;

    /**
     * @var array
     */
    private $calcul = [0,1,2,3,4,5,6,7,8,9,10];

    /**
     * Captcha constructor.
     */
    public function __construct()
    {
        $this->config       = new App();
        $this->session      = Services::session($this->config);
    }

    /**
     * @return string
     */
    public function Create(): string
    {
        $first = rand(0, count($this->calcul) - 1);
        $second = rand(0, count($this->calcul) - 1);
        $resultat = $first + $second;
        $this->session->set('captcha_response', $resultat);
        return $first . ' + ' . $second;
    }

    /**
     * @param $captcha
     *
     * @return bool
     */
    public function Check($captcha): bool
    {
        if ($captcha == $this->session->get('captcha_response')) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function Remove(): bool
    {
        $this->session->remove('captcha_response');
        return true;
    }
}
