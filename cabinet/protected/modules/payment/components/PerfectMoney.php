<?php 
error_reporting (E_ALL );
ini_set('display_errors',1);


class PerfectMoney extends Merchant
{
	public $payment = null;
   
	private $statuses = array(
		'success' 		=> self::STATUS_SUCCESS,
		'wait_secure' 	=> self::STATUS_WAIT,
		'delayed' 		=> self::STATUS_WAIT,
		'failure'		=> self::STATUS_FAIL,
	);   
	
	public function __construct($method = Null) 
	{
        parent::__construct($method);
    }
			
	private function xml2array($originalXML, $attributes=true)
	{
        $xmlArray = array();
        $search = $attributes ? '|<((\S+)(.*))\s*>(.*)</\2>|Ums' : '|<((\S+)()).*>(.*)</\2>|Ums';
       
        // normalize data
        $xml = preg_replace('|>\s*<|', ">\n<", $originalXML); // one tag per line
        $xml = preg_replace('|<\?.*\?>|', '', $xml);            // remove XML declarations
        $xml = preg_replace('|<(\S+?)(.*)/>|U', '<$1$2></$1>', $xml); //Expand singletons
       
        if (! preg_match_all($search, $xml, $xmlMatches))
                return trim($originalXML);      // bail out - no XML found
               
        foreach ($xmlMatches[1] as $index => $key)
        {
                if (! isset($xmlArray[$key])) $xmlArray[$key] = array();       
                $xmlArray[$key][] = $this->xml2array($xmlMatches[4][$index], $attributes);
        }
        return $xmlArray;
	}	
	
	private function getXml()
	{
		$currency = Currencies::model()->findByPk($this->payment->currency);
		$user 	  = Users::model()->findByPk(Yii::app()->user->id);
		
		$alcode=$currency->alphaCode;
		$UE=$alcode{0};
		//U2800591   E3566717
		//exit($this->config['merchant_id']);
		if ($UE=='U'){
			$this->config['merchant_id']='2800591';
		}elseif($UE=='E'){
			$this->config['merchant_id']='3566717';
		}
		
			
			return array(
			'PAYEE_ACCOUNT' => $UE.$this->config['merchant_id'],
			'PAYEE_NAME' => 'FxPrivate',
			'PAYMENT_ID' => $this->payment->id ,
			'PAYMENT_AMOUNT' => $this->payment->amount+0,
			'PAYMENT_UNITS' => $currency->alphaCode,
			'STATUS_URL' => $this->config['server_url'],
			'PAYMENT_URL' => $this->config['result_url'],
			'PAYMENT_URL_METHOD' => 'POST',
			'NOPAYMENT_URL' => $this->config['fail_url'],
			'NOPAYMENT_URL_METHOD' => 'POST',
			'SUGGESTED_MEMO' => '',
			'BAGGAGE_FIELDS' => '',
			'PAYMENT_METHOD' => '',
			
		);
			
		//print_r ($xml);
		//exit('asd23');
		//return $xml;	
	}
	
	public function getStatusCodeByTitle($title)
	{
		if (isset($this->statuses[$title]))
			return $this->statuses[$title];
		else 
			return null;
	}
	
	
	function getSignature()
	{
		$signature = $this->config['signature'];
		
		return base64_encode(sha1($signature.$this->getXml().$signature,1));
	}
	
	function getFormFields($data)
	{
		/* return array(
			'operation_xml'	=> base64_encode($this->getXml()),
			'signature'		=> $this->getSignature(),
		); */
		//exit('qa22222qa');
		return $this->getXml();
		
	}

    
    public function createSuccess() {
        
		//$model = new PayLog();
		//$model->content = $content;
		//$model->save();
    }
    
    public function createFail() {
		
		//$model = new PayLog();
		//$model->content = $content;
		//$model->save();
    }
    
    public function validate() {
		$signature 	= $this->config['signature'];
		
		$hag=strtoupper(md5($signature));
		
		$model = new PayLog();
			ob_start ();
			echo 'PerfectMoneyif TEST1';
			print_r($_REQUEST);
			$content = ob_get_contents();
			$model->content = $content;
			$model->save();		
			ob_end_clean ();
		//-----
		if(isset($_POST['PAYMENT_ID']) && isset ($_POST['PAYEE_ACCOUNT']) && isset ($_POST['PAYMENT_AMOUNT']) 
		&& isset ($_POST['PAYMENT_UNITS']) && isset ($_POST['PAYMENT_BATCH_NUM']) && isset ($_POST['PAYER_ACCOUNT']) && isset ($_POST['TIMESTAMPGMT'])){
			$string=
				  $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
				  $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
				  $_POST['PAYMENT_BATCH_NUM'].':'.
				  $_POST['PAYER_ACCOUNT'].':'.$hag.':'.
				  $_POST['TIMESTAMPGMT'];

			$hash=strtoupper(md5($string));
			
		
			if($hash==$_POST['V2_HASH']){
				$model = new PayLog();
				$model->content = "Validation OK \n";
				$model->save();		

				return true;
			
			}else{
				$model = new PayLog();
				$model->content = "Validation Error \n";
				$model->save();	
				return false; 
			}
		}else{
			
			return false; 
		}
		
    }
		
    public function createForm($payment, $user=null) {
		$this->payment = $payment;
		
		/* return array(
			'operation_xml' => (base64_encode($this->getXml())),
			'signature' 	=> $this->getSignature(),
		); */
		//exit('qa22222qa');
		return $this->getXml();
		
		
    }	
	
    public function setAttributes() {
		/*  $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
			  $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
			  $_POST['PAYMENT_BATCH_NUM'].':'.
			  $_POST['PAYER_ACCOUNT'].':'.$this->config['signature'].':'.
			  $_POST['TIMESTAMPGMT']; */
		
		
		
		///new
		//$encodedData = str_replace(' ','+',$_REQUEST['operation_xml']);
		///
		//$xml		= 	base64_decode($encodedData); 
		//$xml2array 		= $this->xml2array($xml);
		
		
		//$response		= $xml2array['response'][0];
		//$response		= $xml2array['request'][0];
		//echo "<pre>"; print_r($response); exit();
		
		
		$model = new PayLog();
		/* 
		ob_start ();
		echo 'PerfectMoney TEST';
		print_r($response);
		$content = ob_get_contents();
		$model->content = $content;
		$model->save();		
		ob_end_clean (); */
		
		
		/* if (isset($response['status'][0])){
			$this->status 	= $this->getStatusCodeByTitle($response['status'][0]);
		}else{
			$this->status 	= self::STATUS_SUCCESS;
		} */
		
		/* ob_start ();
		
		echo 'PerfectMoney TEST status';
		print_r($this->status);
		$content = ob_get_contents();
		$model->content = $content;
		$model->save();		
		ob_end_clean (); */
		
		$this->orderId 	= $_POST['PAYMENT_ID'];
		$this->payee 	= null;
		$this->amount 	= $_POST['PAYMENT_AMOUNT'];
		
		$payment = Payments::model()->findByPk($this->orderId);	
		
		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }	
}