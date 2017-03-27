<?php

class Qiwi extends Merchant
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
        //echo "success response of qiwi";
        //var_dump($response);
		
    }

    public function createFail() {
        //echo "fail response of qiwi";
        //var_dump($response);
    }

    public function validate()
	{
		ob_start ();
		echo 'QIWI';
		print_r($_REQUEST);
		$content = ob_get_contents();
		ob_end_clean ();
		
		$model = new PayLog();
		$model->content = $content;
		$model->save();
		
		Yii::app()->ishop->updateBill();
		
		if(isset($_REQUEST)) {
			$return = Yii::app()->ishop->checkBill($_REQUEST['order']);
			ob_start ();
			echo 'QIWI-2';
			echo "/n". print_r($return);
			$content = ob_get_contents();
			ob_end_clean ();
			$model = new PayLog();
			$model->content = $content;
			$model->save();
			if (isset($return) && $return->status == 60){
				$this->BILL = $_REQUEST['order'];
				$this->orderId = $_REQUEST['order'];
				$this->QiWi = $return;
				return true;
			}	
		}
		return false;
	}
	
	private function getXml()
	{
		$currency = Currencies::model()->findByPk($this->payment->currency);
		$user 	  = Users::model()->findByPk(Yii::app()->user->id);
				
		/*$xml=
		"<?xml version='1.0' encoding='utf-8'?><request>
			<protocol-version>4.00</protocol-version>
			<request-type>30</request-type>
			<extra name='password'>" . $this->config['secret'] . "</extra>
			<terminal-id>" . $this->config['shopID'] . "</terminal-id>
			<extra name='serial'>" . $this->config['secret'] . "</extra>
			<extra name='comment'>Order ".$this->payment->id."  Deposit Account No.:".$this->payment->target_id."</extra>
			<extra name='to-account'>". substr($user->phone, -10, 10) ."</extra>
			<extra name='amount'>" . $this->payment->amount . "</extra>
			<extra name='trm-id'>" . $this->payment->id . "</extra>
			<extra name='ALARM_SMS'>0</extra>
			<extra name='ACCEPT_CALL'>0</extra>
			<extra name='ltime'>60</extra>
			</request>
		";*/
		$xml="<?xml version='1.0' encoding='utf-8'?><request>
			<protocol-version>4.00</protocol-version>
			<request-type>30</request-type>
			<extra name='password'>".$this->config['secret']."</extra>
			<terminal-id>".$this->config['shopID']."</terminal-id>
			<extra name='comment'>Order ".$this->payment->id."  Deposit Account No.:".$this->payment->target_id."</extra>
			<extra name='to-account'>".substr($user->phone, -10, 10)."</extra>
			<extra name='amount'>".$this->payment->amount."</extra>
			<extra name='txn-id'>".$this->payment->id."</extra>
			<extra name='ALARM_SMS'>0</extra>
			<extra name='ACCEPT_CALL'>0</extra>
			<extra name='ltime'>60</extra>
		</request>
		";
		
		return $xml;
	}
	
    public function createForm($payment, $user=null) {
		
		$this->payment = $payment;
		$user = Users::model()->findByPk(Yii::app()->user->id);
		/*$currency = Currencies::model()->findByPk($this->payment->currency);
		$xml = $this->getXml();
		$opts = array('http' =>
			array(
			'method' => 'POST',
			'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
			."Content-Length: " . strlen($xml) . "\r\n",
			'content' => $xml
			)
		);
		$context = stream_context_create($opts);
		
		return array(
			'qiwi' => (($context)),
		);
		$result = file_get_contents("http://www.mobw.ru/term2/xmlutf.jsp", FALSE, $context);
		*/
		
		/*
		$payment = Payments::model()->findByPk($this->payment->id);
		$secret = $this->config['secret'];
		$hash_string = strtoupper(md5($this->payment->id . strtoupper(md5($secret))));

		$payment->description = $hash_string;
        $payment->save();*/
			ob_start ();
			echo 'QIWI-222';
			echo "/n". print_r(array(
			'from' 		 => $this->config['shopID'],
			'summ' 		 => $payment->amount,
			'com'   	 => "Deposit Account No.:{$payment->target_id}",
			'txn' 	 => $this->payment->id,
			'to' 		 => substr($user->phone, -10, 10),
			'lifetime'	 => '3',
			'check_agt'	 => false,
		));
			$content = ob_get_contents();
			ob_end_clean ();
		$model = new PayLog();
		$model->content = $content;
		$model->save();
		return array(
			'from' 		 => $this->config['shopID'],
			'summ' 		 => $payment->amount,
			'com'   	 => "Deposit Account No.:{$payment->target_id}",
			'txn_id' 	 => $this->payment->id,
			'to' 		 => substr($user->phone, -10, 10),
			'lifetime'	 => '3',
			'check_agt'	 => false,
		);

    }

    public function setAttributes() {
				
		if (isset($this->orderId)){
			$payment = Payments::model()->findByPk($this->orderId);
		
			if ($this->QiWi->amount == $payment->amount && $payment->status == 3){
				$this->payee 	= 'qiwi';
				$this->amount 	= $payment->amount;
				$this->status 	= self::STATUS_SUCCESS;

				$this->targetId 	= $payment->target_id;
				$this->currencyId 	= $payment->currency;
			}else{
				header ("Location: https://my.fx-private.com/payment/payment/success/system/qiwi/method/");
				exit();
			}
		}else{
				header ("Location: https://my.fx-private.com/payment/payment/fail/system/qiwi/method/");
				exit();
		}
    }

}
