<?php

/*
 * BlogCI4 - Blog write with Codeigniter v4dev
 * @author Deathart <contact@deathart.fr>
 * @copyright Copyright (c) 2018 Deathart
 * @license https://opensource.org/licenses/MIT MIT License
 */

namespace App\Libraries;

use App\Models\Admin\ConfigModel;
use Config\Services;

/**
 * Class Mailer
 *
 * @package App\Libraries
 */
class Mailer
{
    /**
     * @var \CodeIgniter\Email\Email|mixed
     */
    protected $email;
    /**
     * @var \App\Models\Admin\ConfigModel
     */
    protected $config_model;

    /**
     * Mailer constructor.
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function __construct()
    {
        $this->config_model = new ConfigModel();
        $this->email = Services::email();

        $this->email->protocol = 'smtp';
        $this->email->SMTPCrypto = $this->config_model->GetConfig('mail_security');
        $this->email->SMTPHost = $this->config_model->GetConfig('mail_host');
        $this->email->SMTPPort = $this->config_model->GetConfig('mail_port');
        $this->email->SMTPUser = $this->config_model->GetConfig('mail_username');
        $this->email->SMTPPass = $this->config_model->GetConfig('mail_password');
    }

    /**
     * @param string $sujet
     * @param string $to
     * @param string $body
     *
     * @return bool
     */
    public function sendmail(string $sujet, string $to, string $body): bool
    {
        $this->email->initialize([
            'SMTPTimeout' => '30',
            'mailType' => 'html',
            'newLine' => "\r\n",
            'CRLF' => "\r\n"
        ]);

        $this->email->setFrom($this->config_model->GetConfig('mail_from_adress'), $this->config_model->GetConfig('mail_from_name'));
        $this->email->setTo($to);
        $this->email->setSubject($sujet);
        $this->email->setMessage($body);

        if ($this->email->send(false)) {
            return true;
        }

        return false;
    }
}
