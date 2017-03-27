<?php 

class rbk extends Merchant
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
        //echo "success response of rbk";
        //var_dump($response);
    }
    
    public function createFail() {
        //echo "fail response of rbk";
        //var_dump($response);
    }
    
    public function validate() {
    	header('Content-Type: text/html; charset=utf-8');   	
		ob_start ();
		echo 'RBK';
		print_r($_POST);
		$content = ob_get_contents();
		ob_end_clean ();
		$model = new PayLog();
		$model->content = $content;
		$model->save();

		switch(intval($_POST['paymentStatus'])) {
			case 5:
				$this->status 	= self::STATUS_SUCCESS;
				break;
			case 4:
				$this->status 	= self::STATUS_FAIL;
				break;				
			case 3:
				$this->status 	= self::STATUS_WAIT;
				break;
		}
	/*		
		$requestsign = mb_strtolower(md5(trim($this->config['eshopId'].'::'.(isset($_POST['orderId'])?$_POST['orderId']:null).'::'.(isset($_POST['serviceName'])?$_POST['serviceName']:null).'::'.(isset($_POST['eshopAccount'])?$_POST['eshopAccount']:null).'::'.(isset($_POST['recipientAmount'])?$_POST['recipientAmount']:null).'::'.(isset($_POST['recipientCurrency'])?$_POST['recipientCurrency']:null).'::'.(isset($_POST['paymentStatus'])?$_POST['paymentStatus']:null).'::'.(isset($_POST['userName'])?$_POST['userName']:null).'::'.(isset($_POST['userEmail'])?$_POST['userEmail']:null).'::'.(isset($_POST['paymentData'])?$_POST['paymentData']:null).'::'.$_POST['secretKey'])));
		if(isset($_GET['dima'])) {
		echo $this->config['eshopId'].'::'.(isset($_POST['orderId'])?$_POST['orderId']:null).'::'.(isset($_POST['serviceName'])?$_POST['serviceName']:null).'::'.(isset($_POST['eshopAccount'])?$_POST['eshopAccount']:null).'::'.(isset($_POST['recipientAmount'])?$_POST['recipientAmount']:null).'::'.(isset($_POST['recipientCurrency'])?$_POST['recipientCurrency']:null).'::'.(isset($_POST['paymentStatus'])?$_POST['paymentStatus']:null).'::'.(isset($_POST['userName'])?$_POST['userName']:null).'::'.(isset($_POST['userEmail'])?$_POST['userEmail']:null).'::'.(isset($_POST['paymentData'])?$_POST['paymentData']:null).'::'.$_POST['secretKey'];
		echo $requestsign.'='.$_POST['hash'];
	}\
*/

		if($_POST['secretKey'] == $this->config['secretKey'] && $this->status == self::STATUS_SUCCESS) {
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
		
		switch($_POST['itemID']){
			case 36: 
				$preference = 'inner';
			break;
			case 37: 
				$preference = 'bankCard';
			break;
			case 38: 
				$preference = 'exchangers';
			break;
			case 39: 
				$preference = 'transfers';
			break;
			case 40: 
				$preference = 'terminals';
			break;
			case 41: 
				$preference = 'sberbank';
			break;
			case 42: 
				$preference = 'postRus';
			break;
			case 43: 
				$preference = 'atm';
			break;
			case 44: 
				$preference = 'ibank';
			break;
			case 45: 
				$preference = 'euroset';
			break;																								
		}		
		$user 	  = Users::model()->findByPk(Yii::app()->user->id);
		$lang 	= in_array($user->country, array(643, 112, 51, 31, 398, 417, 498, 762, 860,804))?'ru':'en';
		return array(
			'eshopId' 				=> $this->config['eshopId'],
			'recipientAmount' 	=> number_format($payment->amount, 2, ',',''),
			'preference'			=> $preference,
			'user_email'		=> $user->email,
			'language'		=> $lang,
			'recipientCurrency' 	=> "{$currency->alphaCode}", 
			'serviceName' 			=> "Deposit Account No.:{$payment->target_id}",
			'orderId' 				=> $this->payment->id,
			'successUrl' 			=> $this->config['successUrl'],
			'failUrl' 			=> $this->config['failUrl'],
		);
			
    }	
	
    public function setAttributes() {
		$this->orderId = $_POST['orderId'];
		$this->amount = $_POST['recipientAmount'];
				
		$payment = Payments::model()->findByPk($this->orderId);	
		
		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }	
}
