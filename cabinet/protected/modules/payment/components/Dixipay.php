<?php 

class Dixipay extends Merchant
{
	public $payment = null;
   	
	public function __construct($method = Null) 
	{
        parent::__construct($method);
    }
	
    public function createSuccess() {
        //echo "success response of webmoney";
       // var_dump($response);
    }
    
    public function createFail() {
        //echo "fail response of webmoney";
      //  var_dump($response);
    }
    
    public function validate() 
	{	
		
		$hash = "";
		$hash .= $_POST["DIXI_ORDER"] . ":";
		$hash .= $_POST["DIXI_ID_TRAN"] . ":";
		$hash .= $this->config['api_key'] . ":";
		$hash .= str_replace(",", ".", $_POST["DIXI_AMOUNT"])*100 . ":";
		$hash .= $_POST["DIXI_CURRENCY"];
		$hash = strtoupper(md5($hash));
		
		$order_id = intval($_POST["DIXI_ORDER"]);

		if ($hash == $_POST["DIXI_HASH"]) {
			return true;
		} else {
			return false;
		}		
    }
		
    public function createForm($payment, $user=null) {
		
		$this->payment = $payment;
		
		$currency = Currencies::model()->findByPk($this->payment->currency);
		
		return array(
			'recipient_account' => $this->config['recipient_account'],
			'recipient_name' 	=> $this->config['recipient_name'],
			'order' 			=> $this->payment->id,
			'product' 			=> "Order #{$this->payment->id}  Deposit Account No.:{$payment->target_id}",
			'amount' 			=> $payment->amount,
			'currency' 			=> $currency->alphaCode,
			'api_key' 			=> md5($this->payment->id . ':' . $this->config['api_key']),
		);
    }	
			
    public function setAttributes() {

		ob_start ();
		echo 'Dixipay';
		print_r($_POST);
		$content = ob_get_contents();
		ob_end_clean ();		
	
		$model = new PayLog();
		$model->content = $content;
		$model->save();		
	
		$this->orderId 	= $_POST['DIXI_ORDER'];
		$this->payee 	= null;
		$this->amount 	= (float) str_replace(',', '', $_POST['DIXI_AMOUNT']);
		$this->status 	= $this->getStatusByDixiStatus($_POST['DIXI_STATUS']);
			
		$payment = Payments::model()->findByPk($this->orderId);	
		
		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }	
	
	function getStatusByDixiStatus($dixiStatus)
	{
		$dixiStatus = (int) $dixiStatus;
		
		if ($dixiStatus == 0) {
			return self::STATUS_SUCCESS;
		} else {
			return self::STATUS_FAIL;
		}
	}
	
}