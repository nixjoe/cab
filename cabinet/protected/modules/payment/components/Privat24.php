<?php 

class Privat24 extends Merchant
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
        //echo "success response of privat24";
        //var_dump($response);
    }
    
    public function createFail() {
        //echo "fail response of privat24";
        //var_dump($response);
    }
    
    public function validate() {
		ob_start ();
		echo 'Privat24';
		print_r($_REQUEST['payment']);
		$content = ob_get_contents();
		ob_end_clean ();
		$model = new PayLog();
		$model->content = $content;
		$model->save();
		

		$pass 	= $this->config['pass'];
		$requestsign = sha1(md5($_REQUEST['payment'].$pass));
		
	   $request = explode('&', $_REQUEST['payment']);
		$tmp_arr = null;
		foreach ( $request as &$items){
			$tmp = explode('=', $items);
			$tmp_arr[$tmp[0]] = $tmp[1];
		}
		
		if($_REQUEST['signature'] == $requestsign && isset($tmp_arr['state']) && $tmp_arr['state'] == 'ok') {
			
			$model = new PayLog();
			$model->content = "Validation OK \n";
			$model->save();		

			return true;
		} else {
		
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
			'merchant' 				=> $this->config['merchant_id'],
			'pay_way' 				=> "privat24",
			'amt' 					=> $payment->amount,
			'ccy' 					=> "{$currency->alphaCode}", 
			'details' 				=> "Deposit Account No.:{$payment->target_id}",
			'ext_details' 			=> "Deposit Account No.:{$payment->target_id}",
			'order' 				=> $this->payment->id,
			'return_url' 			=> $this->config['return_url'],
			'server_url' 			=> $this->config['server_url'],
			
		);
			
    }	
	
    public function setAttributes() {

		$request = explode('&', $_REQUEST['payment']);
		foreach ( $request as &$items){
			$items = explode('=', $items);
		}
		foreach ($request as $itemz){
			if ($itemz[0] == 'order'){
				$this->orderId 	= $itemz[1];
			}elseif($itemz[0] == 'amt'){
				$this->amount 	= $itemz[1];
			}elseif($itemz[0] == 'sender_phone'){
				$this->payee 	= $itemz[1];
			}
		}
		
		$this->status 	= self::STATUS_SUCCESS;
		
		$payment = Payments::model()->findByPk($this->orderId);	
		
		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }	
}