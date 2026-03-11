<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/lib/PHPMailer/Exception.php';
require_once __DIR__ . '/lib/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/lib/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_smtp_mail($subject, $htmlBody, $textBody, $replyToEmail = '', $replyToName = '')
{
    $config = config();
    $mailConfig = $config['mail'] ?? [];
    if (empty($mailConfig['enabled'])) {
        return ['ok' => false, 'error' => 'Mail is disabled.'];
    }

    $mailer = new PHPMailer(true);
    try {
        $mailer->isSMTP();
        $host = (string)($mailConfig['host'] ?? '');
        if (($mailConfig['encryption'] ?? '') === 'ssl' && strpos($host, 'ssl://') !== 0) {
            $host = 'ssl://' . $host;
        }
        $mailer->Host = $host;
        $mailer->Port = (int)($mailConfig['port'] ?? 587);
        $mailer->SMTPAuth = true;
        $mailer->Username = (string)($mailConfig['username'] ?? '');
        $mailer->Password = (string)($mailConfig['password'] ?? '');
        $mailer->SMTPSecure = (string)($mailConfig['encryption'] ?? 'tls');
        $mailer->CharSet = 'UTF-8';

        $fromEmail = (string)($mailConfig['from_email'] ?? '');
        $fromName = (string)($mailConfig['from_name'] ?? '');
        $toEmail = (string)($mailConfig['to_email'] ?? '');
        $toName = (string)($mailConfig['to_name'] ?? '');

        $mailer->setFrom($fromEmail, $fromName);
        $mailer->addAddress($toEmail, $toName);

        if ($replyToEmail !== '') {
            $mailer->addReplyTo($replyToEmail, $replyToName ?: $replyToEmail);
        }

        $mailer->isHTML(true);
        $mailer->Subject = $subject;
        $mailer->Body = $htmlBody;
        $mailer->AltBody = $textBody;

        $mailer->send();
        return ['ok' => true];
    } catch (Exception $e) {
        return ['ok' => false, 'error' => $e->getMessage()];
    }
}
