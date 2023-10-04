<?php

namespace app\src\model\Repository;

class MailRepository
{
    public static function send_mail(array $email, string $subject, string $message): bool
    {
        $headers = "MIME-Version: 1.0" . "\r\n"
            . "Content-type:text/html;charset=UTF-8" . "\r\n"
            . 'From: ' . smtp_username . "\r\n";

        foreach ($email as $to) {
            try {
                $smtpConn = fsockopen(smtp_server, smtp_port, $errno, $errstr, 5);

                if (!$smtpConn) throw new \Exception("SMTP Connection Failed: $errstr ($errno)");

                self::sendCommand($smtpConn, "EHLO example.com");
                $response = fgets($smtpConn, 512);

                if (strpos($response, '250-STARTTLS') !== false) {
                    self::sendCommand($smtpConn, "STARTTLS");
                    stream_socket_enable_crypto($smtpConn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                }

                self::sendCommand($smtpConn, "AUTH LOGIN");
                self::sendCommand($smtpConn, base64_encode(smtp_username));
                self::sendCommand($smtpConn, base64_encode(smtp_password));
                self::sendCommand($smtpConn, "MAIL FROM:<" . smtp_username . ">");
                self::sendCommand($smtpConn, "RCPT TO:<$to>");
                self::sendCommand($smtpConn, "DATA");
                self::sendCommand($smtpConn, "Subject: $subject");
                self::sendCommand($smtpConn, "To: $to");
                self::sendCommand($smtpConn, "$headers");
                self::sendCommand($smtpConn, "$message");
                self::sendCommand($smtpConn, ".");

                fputs($smtpConn, "QUIT\r\n");
                fclose($smtpConn);

            } catch (\Exception $e) {
                return false;
            }
        }
        return true;
    }

    private static function sendCommand($smtpConn, string $command): void
    {
        fputs($smtpConn, $command . "\r\n");
        fgets($smtpConn, 512);
    }

}