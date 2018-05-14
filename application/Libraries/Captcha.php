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
 * Class Captcha
 *
 * @package App\Libraries
 */
class Captcha
{
    /**
     * @var \CodeIgniter\Session\Session
     */
    private $session;

    /**
     * @var array
     */
    private $calcul = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    /**
     * Captcha constructor.
     */
    public function __construct()
    {
        $this->session = Services::session(new App());
    }

    /**
     * @return string
     */
    public function Create(): string
    {
        $first = random_int(0, \count($this->calcul) - 1);
        $second = random_int(0, \count($this->calcul) - 1);
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
        return $captcha === $this->session->get('captcha_response');
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
