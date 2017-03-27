<?php

class Mail {
    public static $errors;

    protected static function log($msg, $type = 3) {
        if (function_exists('error_log')) {
            $msg_str = sprintf("%s: %s\r\n", date('Y-m-d H:i:s'), $msg);
            @error_log($msg_str, $type, dirname(__FILE__).'/../runtime/test_mail.log');
        } else {

        }
    }

    public static function send($template, $params, $recepient, $sender = null) {

        $message = MailTemplates::model()->find(array(
                'condition'=>'name = :template AND language = :language',
                'params'=>array(
                    ':template'=>$template,
                    ':language'=>$params['language'],
                )));

        if (!$message) {
            self::log('Message template "'.$template.'" not found');
            return false;
        }

        foreach ($params as $k=>$v) {
            $message->template = preg_replace("/%$k%/", "$v", $message->template);
        }

        $a = new CEmailValidator();
        if (!$a->validateValue($recepient)) {
            self::log('Invalid recipient "'.$recepient.'"');
            return false;
        };

        $to      = $recepient;
        $subject = $message->subject;
        $message = $message->template;
        $headers = 'From: support@fx-private.com' . "\r\n" .
            'Reply-To: support@fx-private.com' . "\r\n" .
            'Content-type: text/html; charset="utf-8"'. "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        $message = str_replace("\n.", "\n..", $message);

        require_once('mail/class.phpmailer.php');

        $mail             = new PHPMailer(); // defaults to using php "mail()"
			
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Encoding    = "base64";
			$mail->SMTPDebug  = false;  
			$mail->Timeout     = 10;
			$mail->SMTPAuth   = true;
			$mail->IsHTML(true);
			$mail->SMTPSecure = "tls";   
			$mail->AuthType = "PLAIN";
			$mail->Host       = "smtp.gmail.com"; // SMTP server
  			$mail->Port		= 587;
  			$mail->Username   = "support@fx-private.com"; // SMTP account username
  			$mail->Password   = "FyyfLjhjabq1515";        // SMTP account password
  			$mail->CharSet = "UTF-8";
			$mail->SetFrom("support@fx-private.com", "");
			$mail->AddReplyTo("support@fx-private.com", "");
			$mail->Subject    = $subject;
			$mail->MsgHTML($message);
					
			$mail->AddAddress($to, "");
			$result = $mail->Send();
			$mail->ClearAddresses();

        self::log('Sending message: '.print_r(array(
                    'template'=>$template,
                    'subject'=>$subject,
                    'to'=>$to,
                    'result'=>$result?'TRUE':'FALSE'
                ), true));
        //mail($to, $subject, $message, $headers);

        return $result;
    }
}