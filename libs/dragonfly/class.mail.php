<?php

class Mailer
{
    protected $smtp_username = constant('SMTP_USERNAME');
    protected $smtp_password = constant('SMTP_PASSWORD');
    protected $smtp_host = constant('SMTP_HOST');
    protected $smtp_port = constant('SMTP_PORT');
    protected $smtp_secure = 'ssl';

    protected $sender_email = constant('DEFAULT_EMAIL');
    protected $sender_name = constant('DEFAULT_EMAIL_ACCOUNT_NAME');

    public function __construct()
    {
        if (empty($this->smtp_port)) {
            $this->smtp_port = 465;
        }
    }

    /**
     * Send mail to SMTP server
     *
     * @param [type] $receipient_emails
     * @param [type] $subject
     * @param [type] $msg
     * @return void
     */
    public function send_mail($receipient_emails, $subject, $msg)
    {
        require_once(LIBS_DIR . '/vendor/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;

        if (constant('USE_SMTP') == true) {
            //$mail->SMTPDebug = 3;                                 // Enable verbose debug output
            $mail->isSMTP();                                        // Set mailer to use SMTP
            $mail->Host = $this->smtp_host;                         // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                                 // Enable SMTP authentication
            $mail->Username = $this->smtp_username;                 // SMTP username
            $mail->Password = $this->smtp_password;                 // SMTP password
            $mail->SMTPSecure = $this->smtp_secure;                 // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $this->smtp_port;                         // TCP port to connect to
        }

        $mail->From = $this->sender_email;
        $mail->FromName = $this->sender_name;

        if (is_array($receipient_emails)) {
            foreach ($receipient_emails as $email) {
                $mail->addAddress($email); // Add a recipient
            }
        } else {
            $mail->addAddress($receipient_emails); // Add a recipient
        }

        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $msg;
        $mail->AltBody = strip_tags($msg);
        if ($mail->send()) {
            return true;
        } else {
            return  $mail->ErrorInfo;
        }
    }
}
