<?php

// встановлення автозавантажувача Composer
require(__DIR__ . '/../vendor/autoload.php');

use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;

class SMS
{

    public function sendCode(){
        // Initializing SendSingleTextualSms client with appropriate configuration
        $client = new SendSingleTextualSms(new BasicAuthConfiguration('1', '2'));
        // Creating request body
        $requestBody = new SMSTextualRequest();
        $requestBody->setFrom('w');
        $requestBody->setTo(array('d'));
        $requestBody->setText("This is an example message.");
        exit("send");

        try {
            $response = $client->execute($requestBody);
            $sentMessageInfo = $response->getMessages();
            echo "Message ID: " . $sentMessageInfo->getMessageId() . "\n";
            echo "Receiver: " . $sentMessageInfo->getTo() . "\n";
            echo "Message status: " . $sentMessageInfo->getStatus()->getName();
        } catch (Exception $exception) {
            echo "HTTP status code: " . $exception->getCode() . "\n";
            echo "Error message: " . $exception->getMessage();
        }
    }

}