<?php

/**
 * Date: 21.11.2016
 * Time: 19:55
 */
class Okpay extends Merchant
{
    public $payment = null;

    private $statuses = array(
        'success' 		=> self::STATUS_SUCCESS,
        'wait_secure' 	=> self::STATUS_WAIT,
        'delayed' 		=> self::STATUS_WAIT,
        'failure'		=> self::STATUS_FAIL,
    );

    public function __construct($method = Null)
    {
        parent::__construct($method);
    }

    public function createSuccess() {

    }

    public function createFail() {

    }

    public function validate() {

		ob_start ();
		echo 'Okpay';
		print_r($_POST);
		$content = ob_get_contents();
		ob_end_clean ();
		$model = new PayLog();
		$model->content = $content;
		$model->save();
        /* Check IPN and process payment */
        error_reporting(E_ALL ^ E_NOTICE);

        // Read the post from OKPAY and add 'ok_verify'
        $request = 'ok_verify=true';

        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $request .= "&$key=$value";
        }

        $fsocket = false;
        $result = false;

        // Try to connect via SSL due sucurity reason
        if ( $fp = @fsockopen('ssl://checkout.okpay.com', 443, $errno, $errstr, 30) ) {
            // Connected via HTTPS
            $fsocket = true;
        } elseif ($fp = @fsockopen('checkout.okpay.com', 80, $errno, $errstr, 30)) {
            // Connected via HTTP
            $fsocket = true;
        }

        // If connected to OKPAY
        if ($fsocket == true) {
            $header = 'POST /ipn-verify HTTP/1.1' . "\r\n" .
                'Host: checkout.okpay.com'."\r\n" .
                'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
                'Content-Length: ' . strlen($request) . "\r\n" .
                'Connection: close' . "\r\n\r\n";

            @fputs($fp, $header . $request);
            $string = '';
            while (!@feof($fp)) {
                $res = @fgets($fp, 1024);
                $string .= $res;
                // Find verification result in response
                if ( $res == 'VERIFIED' || $res == 'INVALID' || $res == 'TEST') {
                    $result = $res;
                    break;
                }
            }
            @fclose($fp);
        }

        if ($result == 'VERIFIED') {
            // check the "ok_txn_status" is "completed"
            // check that "ok_txn_id" has not been previously processed
            // check that "ok_receiver_email" is your OKPAY email
            // check that "ok_txn_gross"/"ok_txn_currency" are correct
            // process payment

            $model = new PayLog();
            $model->content = "Validation OK \n";
            $model->save();

            return true;

        } elseif($result == 'INVALID') {
            $model = new PayLog();
            $model->content = "Validation Error \n";
            $model->save();
			return false;

        } elseif($result == 'TEST') {
            // If 'TEST': do something
            $model = new PayLog();
            $model->content = "Validation OK \n";
            $model->save();

            return true;

        } else {
            // IPN not verified or connection errors
            // If status != 200 IPN will be repeated later
            $model = new PayLog();
            $model->content = "Validation Error \n";
            $model->save();

            return false;
        }
    }

    public function createForm($payment, $user=null) {
        $this->payment = $payment;
        $currency = Currencies::model()->findByPk($this->payment->currency);
        return array(
            'ok_item_1_name'            => "Deposit to client account {$payment->target_id}",
			'ok_receiver'				=> 'OK586068665',
            'ok_currency' 	            => "{$currency->alphaCode}",
            'ok_item_1_price' 			=> $payment->amount,
			'order' 				=> $this->payment->id,
			'ok_item_1_custom_1_value' => $this->payment->id,
            'ok_return_success' 			=> $this->config['result_url'],
            'ok_return_fail' 			=> $this->config['fail_url'],
            'ok_ipn' 			=> $this->config['server_url'],
        );
    }

    public function setAttributes() {

        if ($_POST['order']){
            $this->orderId 	= $_POST['order'];
        }
		if ($_POST['ok_item_1_custom_1_value']) {
			$this->orderId 	= $_POST['ok_item_1_custom_1_value'];
		}
        if ($_POST['ok_item_1_price']){
            $this->amount 	= $_POST['ok_item_1_price'];
        }
        if ($_POST['ok_payer_phone']){
            $this->payee 	= $_POST['ok_payer_phone'];
        }

        $this->status 	= self::STATUS_SUCCESS;

        $payment = Payments::model()->findByPk($this->orderId);
        $this->targetId 	= $payment->target_id;
        $this->currencyId 	= $payment->currency;
    }
}