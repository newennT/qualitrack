<?php 

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Attachment;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerService
{
    private MailerInterface $mailer;
    private ParameterBagInterface $params;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->mailer = $mailer;
    }

    public function sendAudit(string $sendTo, string $subject, string $body, string $attachmentPath = null): bool
    {
        try {
            $email = (new Email())
                ->from(new Address($this->params->get('mailer_from'), 'Sicomen'))
                ->to($sendTo)
                ->subject($subject)
                ->html($body);

            if ($attachmentPath && file_exists($attachmentPath)) {
                $email->attachFromPath($attachmentPath);
            }

            $this->mailer->send($email);

            return true;
        } catch (TransportExceptionInterface $e) {
            error_log("Erreur d'envoi d'email : " . $e->getMessage());
            return false;
        }
    }
}