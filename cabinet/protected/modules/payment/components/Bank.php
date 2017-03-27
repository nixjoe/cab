<?php 

class Bank extends Merchant
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
       // var_dump($response);
    }
    
    public function validate() 
	{	
			return true;
    }
		
    public function createForm($payment, $user=null) {
		
		$this->payment = $payment;
		
		$currency = Currencies::model()->findByPk($this->payment->currency);
		$user = Users::model()->findByPk(Yii::app()->user->getId());
		
		return array(
			'order' 			=> $this->payment->id,
			'payment_id' 			=> $payment->target_id,
			'username'			=> ($user->familyName . " " . $user->givenName . " " . $user->middleName),
			'regdate'			=> ($user->regdate),
			'amount' 			=> $payment->amount,
			'currency' 			=> $currency->alphaCode,
		);
    }	
			
    public function setAttributes() {

		ob_start ();
		echo 'bank';
		print_r($_POST);
		$content = ob_get_contents();
		ob_end_clean ();		
	
		$model = new PayLog();
		$model->content = $content;
		$model->save();		
    }		
}