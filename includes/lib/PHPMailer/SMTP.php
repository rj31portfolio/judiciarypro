<?php
namespace PHPMailer\PHPMailer;

class SMTP
{
    protected $socket;

    public function connect($host, $port)
    {
        $this->socket = @fsockopen($host, $port, $errno, $errstr, 15);
        if (!$this->socket) {
            return false;
        }
        return $this->getResponse(220);
    }

    public function hello()
    {
        $this->send('EHLO ' . $this->getHostname());
        return $this->getResponse(250);
    }

    public function startTLS()
    {
        $this->send('STARTTLS');
        if (!$this->getResponse(220)) {
            return false;
        }
        return stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
    }

    public function auth($username, $password)
    {
        $this->send('AUTH LOGIN');
        if (!$this->getResponse(334)) {
            return false;
        }
        $this->send(base64_encode($username));
        if (!$this->getResponse(334)) {
            return false;
        }
        $this->send(base64_encode($password));
        return $this->getResponse(235);
    }

    public function mailFrom($address)
    {
        $this->send('MAIL FROM:<' . $address . '>');
        return $this->getResponse(250);
    }

    public function rcptTo($address)
    {
        $this->send('RCPT TO:<' . $address . '>');
        return $this->getResponse(250);
    }

    public function data($data)
    {
        $this->send('DATA');
        if (!$this->getResponse(354)) {
            return false;
        }
        $this->send($this->escapeData($data) . "\r\n.");
        return $this->getResponse(250);
    }

    public function quit()
    {
        if ($this->socket) {
            $this->send('QUIT');
            fclose($this->socket);
            $this->socket = null;
        }
    }

    protected function send($command)
    {
        if ($this->socket) {
            fwrite($this->socket, $command . "\r\n");
        }
    }

    protected function getResponse($code)
    {
        if (!$this->socket) {
            return false;
        }
        $response = '';
        while (($line = fgets($this->socket, 515)) !== false) {
            $response .= $line;
            if (preg_match('/^\d{3}\s/', $line)) {
                break;
            }
        }
        return substr($response, 0, 3) == (string)$code;
    }

    protected function escapeData($data)
    {
        $lines = preg_split('/\r\n|\r|\n/', $data);
        foreach ($lines as &$line) {
            if (isset($line[0]) && $line[0] === '.') {
                $line = '.' . $line;
            }
        }
        return implode("\r\n", $lines);
    }

    protected function getHostname()
    {
        $host = $_SERVER['SERVER_NAME'] ?? 'localhost';
        return $host ?: 'localhost';
    }
}
