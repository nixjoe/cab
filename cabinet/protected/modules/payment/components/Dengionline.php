<?php

class Dengionline extends Merchant
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

	public function getStatusCodeByTitle($title)
	{
		if (isset($this->statuses[$title]))
			return $this->statuses[$title];
		else
			return null;
	}

    public function createSuccess() {
       // echo "success response of dengionline";
        //var_dump($response);
    }

    public function createFail() {
        //echo "fail response of dengionline";
        //var_dump($response);
    }

    public function validate()
	{
		ob_start ();
		echo 'Dengionline';
		print_r($_REQUEST);
		$content = ob_get_contents();
		ob_end_clean ();
		
		$model = new PayLog();
		$model->content = $content;
		$model->save();
		
		$secretKey = $this->config['pass'];
		$projectHash = md5($_POST['amount'].$_POST['userid'].$_POST['paymentid'].$secretKey);

		if($projectHash != $_POST['key']){
			 return false;
		}else{
			return true;
		}
    }

    public function createForm($payment, $user=null) {

		$this->payment = $payment;
		
		$currency = Currencies::model()->findByPk($this->payment->currency);
		switch($currency->curID){
			case 643: 
			$modtype = 108;
			break;
			case 840: 
			$modtype = 110;
			break;
			case 978: 
			$modtype = 109;
			break;
			default: 
			$modtype = 107;
			break;
		}

		switch($_POST['itemID']){
			case 16: 
				$modtype = 124;
			break;
			case 17: 
				$modtype = 76;
			break;
			case 18: 
				$modtype = 74;
			break;
			case 19: 
				$modtype = 204;
			break;
			case 20: 
				$modtype = 32;
			break;
			case 21: 
				$modtype = 66;
			break;
			case 22: 
				$modtype = 7;
			break;
			case 23: 
				$modtype = 75;
			break;
			case 24: 
				$modtype = 54;
			break;
			case 25: 
				$modtype = 62;
			break;
			case 26: 
				$modtype = 11;
			break;
			case 27: 
				$modtype = 112;
			break;
			case 28: 
				$modtype = 246;
			break;
			case 29: 
				$modtype = 71;
			break;
			case 30: 
				$modtype = 42;
			break;
			case 31: 
				$modtype = 18;
			break;
			case 32: 
				$modtype = 41;
			break;
			case 33: 
				$modtype = 70;
			break;
			case 34: 
				$modtype = 37;
			break;
			case 35: 
				$modtype = 64;
			break;
			default: 
				$modtype = 107;
			break;
		}

		
		return array(
			'amount' 			=> $payment->amount,
			'order_id' 			=> $this->payment->id,
			'mode_type' 		=> $modtype,
			'paymentCurrency' 	=> "{$currency->alphaCode}", 
			'nickname' 			=> $this->payment->id,
			'project'			=> $this->config['project'],
			'comment' 			=> "Order #{$this->payment->id}  Deposit Account No.:{$payment->target_id}",
			/*'LMI_SUCCESS_URL' 		=> $this->config['LMI_SUCCESS_URL'] . '/' . $this->method,
			'LMI_RESULT_URL' 			=> $this->config['LMI_RESULT_URL'] . '/' . $this->method,
			'LMI_FAIL_URL' 			=> $this->config['LMI_FAIL_URL'] . '/' . $this->method,*/
			
			
		);
    }

    public function setAttributes() {

	
		$this->orderId 	= $_POST['orderid'];
		$this->payee 	= $_POST['paymentid'];
		$this->status 	= self::STATUS_SUCCESS;

		$payment = Payments::model()->findByPk($this->orderId);
		
		$this->amount = $payment['amount'];

		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }
}