<?php 

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerService
{
    private PHPMailer $phpMailer;
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->mailer = new PHPMailer(true);
        
        $this->mailer->CharSet = PHPMailer::CHARSET_UTF8;
        $this->mailer->isSMTP();
        $this->mailer->Host = $this->params->get('mailer_host');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->params->get('mailer_username');
        $this->mailer->Password = $this->params->get('mailer_password');
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port = $this->params->get('mailer_port');
        $this->mailer->setFrom($this->params->get('mailer_from'), 'Sicomen');
    }

    public function sendAudit(string $sendTo, string $subject, string $body, string $attachmentPath = null): bool 
    {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($sendTo);
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;

            if ($attachmentPath && file_exists($attachmentPath)) {
                $this->mailer->addAttachment($attachmentPath);
            }

            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email : " . $this->mailer->ErrorInfo);
            return false;
        }
    }
}