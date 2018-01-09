<?php namespace App\Libraries;

use App\Models\Admin\ConfigModel;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Class Mailer
 *
 * @package App\Libraries
 */
class Mailer
{

    /**
     * @var \Swift_Mailer
     */
    protected $mail;
    /**
     * @var \App\Models\Admin\ConfigModel
     */
    protected $config_model;

    /**
     * Mailer constructor.
     */
    public function __construct()
    {
        $this->config_model = new ConfigModel();
        $transport = new Swift_SmtpTransport($this->config_model->GetConfig('mail_host'), $this->config_model->GetConfig('mail_port'));

        if ($this->config_model->GetConfig('mail_security') != null) {
            $transport->setEncryption($this->config_model->GetConfig('mail_security'));
        }

        if ($this->config_model->GetConfig('mail_username') != null) {
            $transport->setUsername($this->config_model->GetConfig('mail_username'));
        }

        if ($this->config_model->GetConfig('mail_password') != null) {
            $transport->setPassword($this->config_model->GetConfig('mail_password'));
        }

        $this->mail = new Swift_Mailer($transport);
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
        $message = (new Swift_Message($sujet))
            ->setFrom([$this->config_model->GetConfig('mail_from_adress') => $this->config_model->GetConfig('mail_from_name')])
            ->setTo($to)
            ->setBody($body);

        $this->mail->send($message);

        return true;
    }
}
