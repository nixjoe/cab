<?php 

class Liqpay extends Merchant
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
				
		$xml="<request>      
				<version>1.2</version>
				<result_url>" . $this->config['result_url'] . $this->method . "</result_url>
				<server_url>" . $this->config['server_url'] . $this->method . "</server_url>
				<merchant_id>" . $this->config['merchant_id'] . "</merchant_id>
				<order_id>" . $this->payment->id . "</order_id>
				<amount>" . $this->payment->amount . "</amount>
				<currency>" . $currency->alphaCode . "</currency>
				<description> Order ".$this->payment->id."  Deposit Account No.:".$this->payment->target_id." </description>
				<default_phone>" . $user->phone . "</default_phone>
				<pay_way>" . $this->method . "</pay_way> 
			</request>";	
		
		return $xml;	
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
		return array(
			'operation_xml'	=> base64_encode($this->getXml()),
			'signature'		=> $this->getSignature(),
		);
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
		///new
		$encodedData = str_replace(' ','+',$_REQUEST['operation_xml']);
		///
		$xml		= base64_decode($encodedData); 
		$sign		= base64_encode(sha1($signature.$xml.$signature,1)); 
		
		$requestsign = str_replace(' ','+',$_REQUEST['signature']);
		//echo $sign; echo "<br>"; echo $requestsign;
		//if($sign == $_REQUEST['signature']) {
		
		$model = new PayLog();
		ob_start ();
		echo 'liqpay TEST1';
		print_r($_REQUEST);
		$content = ob_get_contents();
		$model->content = $content;
		$model->save();		
		ob_end_clean ();
		
		$status_dd = $this->xml2array($xml);
		$stat_suc=$status_dd['response'][0]['status'][0];
		
		if($sign == $requestsign && $stat_suc=='success' ) {
			
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
		
		return array(
			'operation_xml' => (base64_encode($this->getXml())),
			'signature' 	=> $this->getSignature(),
		);
    }	
	
    public function setAttributes() {

		///new
		$encodedData = str_replace(' ','+',$_REQUEST['operation_xml']);
		///
		$xml		= 	base64_decode($encodedData); 
		$xml2array 		= $this->xml2array($xml);
		
		
		$response		= $xml2array['response'][0];
		//$response		= $xml2array['request'][0];
		//echo "<pre>"; print_r($response); exit();
		$this->orderId 	= $response['order_id'][0];
		$this->payee 	= isset($response['sender_phone'][0]) ? $response['sender_phone'][0] : $response['default_phone'][0];
		$this->amount 	= $response['amount'][0];
		
		/*$model = new PayLog();
		ob_start ();
		echo 'liqpay TEST';
		print_r($response);
		$content = ob_get_contents();
		$model->content = $content;
		$model->save();		
		ob_end_clean ();*/
		
		
		if (isset($response['status'][0])){
			$this->status 	= $this->getStatusCodeByTitle($response['status'][0]);
		}else{
			$this->status 	= self::STATUS_SUCCESS;
		}
		
		/*ob_start ();
		
		echo 'liqpay TEST status';
		print_r($this->status);
		$content = ob_get_contents();
		$model->content = $content;
		$model->save();		
		ob_end_clean ();*/
		
		$payment = Payments::model()->findByPk($this->orderId);	
		$this->targetId 	= $payment->target_id;
		$this->currencyId 	= $payment->currency;
    }	
}