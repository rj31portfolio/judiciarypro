<?php
/**
 * PHPMailer - PHP email creation and transport class.
 * Simplified distribution for this project.
 */
namespace PHPMailer\PHPMailer;

class PHPMailer
{
    const CRLF = "\r\n";

    public $isSMTP = false;
    public $Host = 'localhost';
    public $Port = 25;
    public $SMTPAuth = false;
    public $Username = '';
    public $Password = '';
    public $SMTPSecure = '';
    public $CharSet = 'UTF-8';
    public $Subject = '';
    public $Body = '';
    public $AltBody = '';

    protected $from = ['email' => '', 'name' => ''];
    protected $replyTo = ['email' => '', 'name' => ''];
    protected $to = [];
    protected $isHtml = false;

    public function isSMTP()
    {
        $this->isSMTP = true;
    }

    public function setFrom($address, $name = '')
    {
        $this->from = ['email' => $address, 'name' => $name];
    }

    public function addAddress($address, $name = '')
    {
        $this->to[] = ['email' => $address, 'name' => $name];
    }

    public function addReplyTo($address, $name = '')
    {
        $this->replyTo = ['email' => $address, 'name' => $name];
    }

    public function isHTML($isHtml = true)
    {
        $this->isHtml = (bool)$isHtml;
    }

    public function send()
    {
        if (!$this->isSMTP) {
            throw new Exception('SMTP is required.');
        }

        if (empty($this->to)) {
            throw new Exception('No recipients defined.');
        }

        $smtp = new SMTP();
        if (!$smtp->connect($this->Host, $this->Port)) {
            throw new Exception('SMTP connect failed.');
        }

        if (!$smtp->hello()) {
            throw new Exception('SMTP hello failed.');
        }

        if ($this->SMTPSecure === 'tls') {
            if (!$smtp->startTLS()) {
                throw new Exception('SMTP STARTTLS failed.');
            }
            if (!$smtp->hello()) {
                throw new Exception('SMTP hello failed after TLS.');
            }
        }

        if ($this->SMTPAuth) {
            if (!$smtp->auth($this->Username, $this->Password)) {
                throw new Exception('SMTP auth failed.');
            }
        }

        if (!$smtp->mailFrom($this->from['email'])) {
            throw new Exception('SMTP MAIL FROM failed.');
        }

        foreach ($this->to as $recipient) {
            if (!$smtp->rcptTo($recipient['email'])) {
                throw new Exception('SMTP RCPT TO failed.');
            }
        }

        $headers = [];
        $headers[] = 'From: ' . $this->formatAddress($this->from['email'], $this->from['name']);
        $headers[] = 'To: ' . $this->formatAddressList($this->to);
        if (!empty($this->replyTo['email'])) {
            $headers[] = 'Reply-To: ' . $this->formatAddress($this->replyTo['email'], $this->replyTo['name']);
        }
        $headers[] = 'MIME-Version: 1.0';
        if ($this->isHtml) {
            $headers[] = 'Content-Type: text/html; charset=' . $this->CharSet;
        } else {
            $headers[] = 'Content-Type: text/plain; charset=' . $this->CharSet;
        }
        $headers[] = 'Subject: ' . $this->encodeHeader($this->Subject);

        $data = implode(self::CRLF, $headers) . self::CRLF . self::CRLF;
        $data .= $this->isHtml ? $this->Body : $this->AltBody;

        if (!$smtp->data($data)) {
            throw new Exception('SMTP DATA failed.');
        }

        $smtp->quit();
        return true;
    }

    protected function formatAddress($email, $name)
    {
        $email = trim($email);
        $name = trim($name);
        if ($name === '') {
            return $email;
        }
        return sprintf('"%s" <%s>', $this->encodeHeader($name), $email);
    }

    protected function formatAddressList($list)
    {
        $items = [];
        foreach ($list as $entry) {
            $items[] = $this->formatAddress($entry['email'], $entry['name']);
        }
        return implode(', ', $items);
    }

    protected function encodeHeader($text)
    {
        return '=?' . $this->CharSet . '?B?' . base64_encode($text) . '?=';
    }
}
