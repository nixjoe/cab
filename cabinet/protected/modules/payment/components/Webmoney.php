<?php

class Webmoney extends Merchant
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
        //echo "success response of webmoney";
        //var_dump($response);
    }

    public function createFail() {
        //echo "fail response of webmoney";
        //var_dump($response);
    }

    public function validate()
	{

		if (isset($_REQUEST['LMI_SECRET_KEY'])) {
			$secret_key	= trim($_REQUEST['LMI_SECRET_KEY']);

			if($secret_key == $this->config['secret_key']) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
    }

    public function createForm($payment, $user=null) {

		$this->payment = $payment;

		return array(
			'LMI_PAYMENT_AMOUNT' 		=> $payment->amount,
			'LMI_PAYMENT_DESC_BASE64' 	=> base64_encode("Order #{$this->payment->id}  Deposit Account No.:{$payment->target_id}"),
			'LMI_PAYEE_PURSE' 			=> $this->config[$this->method],
			'LMI_SUCCESS_URL' 		=> $this->config['LMI_SUCCESS_URL'] . '/' . $this->method,
			'LMI_RESULT_URL' 			=> $this->config['LMI_RESULT_URL'] . '/' . $this->method,
			'LMI_FAIL_URL' 			=> $this->config['LMI_FAIL_URL'] . '/' . $this->method,
			'LMI_SIM_MODE' => '',
			'LMI_PAYMENT_NO' 			=> $this->payment->id,
		);
    }

    public function setAttributes() {

	
		$this->orderId 	= $_POST['LMI_PAYMENT_NO'];
		$this->payee 	= $_POST['LMI_PAYER_PURSE'];
		$this->amount 	= $_POST['LMI_PAYMENT_AMOUNT'];
		$this->status 	= self::STATUS_SUCCESS;

		$payment = Payments::model()->findByPk($this->orderId);

		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }
}