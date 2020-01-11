<?php
namespace App;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Mail
{
    public static function send($subject, $body, $to) {
        $transport = (new Swift_SmtpTransport(Config::mailHost, Config::mailPort))
            ->setUsername(Config::mailUser)
            ->setPassword(Config::mailPass);

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
            ->setFrom([Config::mailFrom => Config::siteName])
            ->setTo([$to])
            ->setBody($body, 'text/html');

        return $mailer->send($message);
    }
}
