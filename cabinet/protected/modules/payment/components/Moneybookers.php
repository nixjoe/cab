<?php

class Moneybookers extends Merchant
{
	public $payment = null;

	private $statuses = array(
		'success' 		=> self::STATUS_SUCCESS,
		'wait_secure' 	=> self::STATUS_WAIT,
		'failure'		=> self::STATUS_FAIL,
	);

	public function __construct($method = Null)
	{
        parent::__construct($method);
    }

    public function createSuccess() {
        //echo "success response of Moneybookers";
        //var_dump($response);
    }

    public function createFail() {
        //echo "fail response of Moneybookers";
        //var_dump($response);
    }

    public function validate()
	{
		
		// Validate the Moneybookers signature
		$concatFields = $_POST['merchant_id']
			.$_POST['transaction_id']
			.strtoupper(md5($this->config['secret']))
			.$_POST['mb_amount']
			.$_POST['mb_currency']
			.$_POST['status'];
		

		//$MBEmail = 'merchant-email@example.com';
		$MBEmail = $this->config['pay_to_email'];
		// Ensure the signature is valid, the status code == 2,
		// and that the money is going to you
		if (strtoupper(md5($concatFields)) == $_POST['md5sig']
			&& $_POST['status'] == 2
			&& $_POST['pay_to_email'] == $MBEmail)
		{
			
			
			$model = new PayLog();
			$model->content = "Validation OK \n";
			$model->save();	
			return true;

		}
		else
		{
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
			'language' 				=> 'RU',
			'pay_to_email' 			=> $this->config['pay_to_email'],
			'status_url' 			=> $this->config['status_url'],
			'return_url' 			=> $this->config['return_url'],
			'cancel_return' 		=> $this->config['cancel_return'],
			'order_id' 				=> $this->payment->id,
			'transaction_id'				=> $this->payment->id,
			'detail1_description' 	=> "Order #{$this->payment->id}  Deposit Account No.:{$payment->target_id}",
			'detail1_text' 			=> "{$payment->target_id}",
			'amount' 				=> $payment->amount,
			'currency' 				=> $currency->alphaCode,
		);
	}

    public function setAttributes() {

		/*ob_start ();
		//echo __CLASS__;
		print_r($_POST);
		$content = ob_get_contents();
		ob_end_clean ();

		$model = new PayLog();
		$model->content = $content;
		$model->save();
		*/
		
		$this->orderId 	= $_POST['transaction_id'];
		$this->payee 	= $_POST['merchant_id'];
		$this->amount 	= $_POST['mb_amount'];
		$this->status 	= self::STATUS_SUCCESS;

		$payment = Payments::model()->findByPk($this->orderId);

		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
		
    }

}