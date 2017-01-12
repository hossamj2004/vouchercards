<?php
include_once(__DIR__.'/email/PHPMailer/PHPMailerAutoload.php');
/**
 * Class Email
 * this class is responsible on sending emails
 */
class Email {
    /**
     * @param $html
     * @param $subject
     * @param $toEmail
     * @param string $fromEmail
     * @param string $fromName
     * @param array $headers
     * @return bool
     * send mails
     */
    static function sendEmail($html,$subject,$toEmail,
                                      $fromEmail = '',$fromName = 'egyptjudgesclub.com'
                                      ,$headers = array('Reply-To' => 'no reply')){
        //using mail function  todo change in future
        $configuration= \Phalcon\Di::getDefault()->getShared('configuration');
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';

        $mail->Host       = $configuration->smtp['host'];// "smtp.gmail.com"; // SMTP server example
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->Port       = $configuration->smtp['port'];// 587;                    // set the SMTP port for the GMAIL server
        $mail->Username   = $configuration->smtp['Username'];// // SMTP account username example
        $mail->Password   = $configuration->smtp['password'];//

        $mail->From =$fromEmail;
        $mail->FromName = $fromName;
        if( isset( $configuration->smtp['developerEmail']) )
            $mail->AddAddress($configuration->smtp['developerEmail']);
        $mail->AddAddress($toEmail);
        $mail->IsHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $html;
        $mail->AltBody = $html;
        if(!$mail->Send())
        {
            return false;
        }
        return true;
    }
}
