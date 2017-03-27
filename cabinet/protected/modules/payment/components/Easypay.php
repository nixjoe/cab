<?php

class Easypay extends Merchant
{
	public $payment = null;

	public function __construct($method = Null)
	{
        parent::__construct($method);
    }

    public function createSuccess() {
        //echo "success response of webmoney";
        //var_dump($response);
    }

    public function createFail() {
        //echo "fail response of webmoney";
        //var_dump($response);
    }

    public function validate()
	{
		if (isset($_POST)) {

			ob_start ();
			if (isset($_POST))
				print_r($_POST);
			$content = ob_get_contents();
			ob_end_clean ();

			$model = new PayLog();
			$model->content = $content;
			$model->save();

			$params = array(
				$this->config['merchant_id'],
				$_POST['order_id'],
				$_POST['payment_id'],
				$_POST['desc'],
				$_POST['payment_type'],
				$_POST['amount'],
				$_POST['commission'],
				//$this->config['secret_key'],
				'45b07da28ac147ea8fb1eac5a1403692',
			);

			$hash = hash('sha256', implode(';', $params));

			if ($hash == $_POST['sign']) {
				return true;
			} else {

				ob_start ();
				$model = new PayLog();
				echo 'Validation ERROR';
				$model->content = $content;
				$model->save();
				$content = ob_get_contents();
				ob_end_clean ();

				return true;
			}

		} else {
			return false;
		}
	}

    public function createForm($payment, $user=null) {

		$this->payment = $payment;

		$currency = Currencies::model()->findByPk($this->payment->currency);

		return array(
			'merchant_id' 		=> $this->config['merchant_id'],
			'order_id' 			=> $this->payment->id,
			'desc' 				=> "Order #{$this->payment->id}  Deposit Account No.:{$payment->target_id}",
			'amount' 			=> $payment->amount,
		);
	}

    public function setAttributes() {

		ob_start ();
		echo 'EasyPay';
		print_r($_POST);
		$content = ob_get_contents();
		ob_end_clean ();

		$model = new PayLog();
		$model->content = $content;
		$model->save();

		$this->orderId 	= $_POST['order_id'];
		$this->payee 	= null;
		$this->amount 	= $_POST['amount'];
		$this->status 	= self::STATUS_SUCCESS;

		$payment = Payments::model()->findByPk($this->orderId);

		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }

}