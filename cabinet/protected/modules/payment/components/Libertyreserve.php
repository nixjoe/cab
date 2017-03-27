<?php

class Libertyreserve extends Merchant
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
	
        //echo "success response of Libertyreserve";
        //var_dump($response);
    }

    public function createFail() {
        //echo "fail response of Libertyreserve";
        //var_dump($response);
    }

    public function validate()
	{
		
		/*ob_start ();
		echo 'Libertyreserve';
		print_r($_REQUEST);
		$content = ob_get_contents();
		ob_end_clean ();
		
		$model = new PayLog();
		$model->content = $content;
		$model->save();
		*/
		
		$str =
		  $_REQUEST["lr_paidto"].":".
		  $_REQUEST["lr_paidby"].":".
		  stripslashes($_REQUEST["lr_store"]).":".
		  $_REQUEST["lr_amnt"].":".
		  $_REQUEST["lr_transfer"].":".
		  $_REQUEST["lr_currency"].":".
		  $this->config['security_word'];
		  
		
		 $hash = strtoupper(bin2hex(mhash(MHASH_SHA256, $str)));
		
		
		
		if (isset($_REQUEST["lr_paidto"]) &&
			$_REQUEST["lr_paidto"] == strtoupper($this->config['lr_acc']) &&
			isset($_REQUEST["lr_store"]) &&
			stripslashes($_REQUEST["lr_store"]) == $this->config['lr_store'] &&
			isset($_REQUEST["lr_encrypted"]) &&
			$_REQUEST["lr_encrypted"] == $hash)
		{
			return true;
		} else {
			return false;
		}

	}

    public function createForm($payment, $user=null) {

		$this->payment = $payment;

		$currency = Currencies::model()->findByPk($this->payment->currency);

		return array(
			'lr_acc' 		=> $this->config['lr_acc'],
			'lr_store' 		=> $this->config['lr_store'],
			'lr_amnt' 		=> $payment->amount,
			'lr_currency' 	=> "LR{$currency->alphaCode}", 
			'lr_comments' 	=> "Deposit Account No.:{$payment->target_id}",
			'order_id' 		=> $this->payment->id,
			/*'lr_success_url' => $this->config['return_url'],
			'lr_success_url_method' => $this->config['method'],
			'lr_fail_url' => $this->config['cancel_return'],
			'lr_fail_url_method' => $this->config['method'],
			'lr_status_url' => $this->config['status_url'],
			'lr_status_url_method' => $this->config['method'],*/
		);
    }

    public function setAttributes() {

		/*
		ob_start ();
		echo 'Libertyreserve';
		print_r($_REQUEST);
		$content = ob_get_contents();
		ob_end_clean ();
		*/

		

		$this->orderId 	= $_REQUEST['order_id'];
		$this->payee 	= $_REQUEST["lr_paidby"];
		$this->amount 	= $_REQUEST['lr_amnt'];
		$this->status 	= self::STATUS_SUCCESS;

		$payment = Payments::model()->findByPk($this->orderId);

		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }
}