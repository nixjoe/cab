<?php

class PaymentController extends Controller
{
    public $user;

	public function __construct($id, $module = null) {

		parent::__construct($id, $module);
						
		if (!Yii::app()->user->isGuest) {
			$this->user = Users::model()->findByPk(Yii::app()->user->getId());
        }
    }


	public function filters()
    {
        return array(
            'accessControl',
        );
    }

	public function accessRules()
    {

        return array(
            array('deny',
                'actions'=>array('index', 'success', 'fail'),
                'users'=>array('?'),

            ),
            array('allow',
                'actions'=>array('result, index, easypay, qiwi'),
                'users'=>array('*'),
            ),
        );
    }

	public function actionIndex()
        {
            $ok = false;
            $payform = new PaymentForm('primarysubmit');
            if (isset($_POST['PaymentForm'])) {

                $payform->attributes = $_POST['PaymentForm'];
                $ok = $payform->validate();
            }

            if (!isset($_POST['PaymentForm']) || !$ok){
                $tradeaccounts = $this->user->tradeaccounts_();

                $transits = $this->user->transitaccounts_(array('with'=>'currency_'));
                $request = array();
                foreach ($tradeaccounts as $key=>$val) {
                    $request[] = intval($val['mtID']);
                }
                $mtdata = array();
                if (!empty ($request)) {

                    //$mt = new MTconnector('78.140.130.240:443', 11, 'qwerty1');
					$mqApi = Yii::app()->params['mqApi'];
					$mt = new MTconnector($mqApi['host'] . '.' . $mqApi['port'], $mqApi['login'], $mqApi['password']);
                    if($mt->connected())
                        {
                            $mt4accounts = $mt->find($request);
                            foreach ($mt4accounts as $key=>$val){
                                $mtdata[$val['login']]['leverage'] = $val['leverage'];
                                $mtdata[$val['login']]['balance'] = $val['balance'];
								//TODO: Добавить сохранение данных в кеш!!!
                                //Tradeaccounts::model()->
                            }
                        }
                }
                $this->render('cashin', array(
                    'payform' => $payform,
                    'tradeaccounts'=>$tradeaccounts,
                    'mtdata'=>$mtdata,
                    'transitID'=>$this->user->transitID,
                    'transits'=>$transits,
                    ));
            } else {


                $payform->setScenario('intersubmit');
                $a = explode('-', $payform->target);
                //die($a[1]);
                if (count($a)>1) {
                    $curID = $a[1];
                    $shop = IkShops::model()->find('cur_id = "' . $curID .  '"');
                    $type = 4;
                } else {
                    $curID = 840;
                    $shop = IkShops::model()->find('cur_id = "840"');
                    $type = 5;
                }

				$connection	= Yii::app()->db;
				$sql = "SELECT *
						FROM paycurrency AS c
						LEFT JOIN paymethods AS m ON c.method_id = m.id
						WHERE c.enabled = 1 AND m.enabled = 1 AND c.currency_id = {$curID}
						";
				$paymethods = $connection->createCommand($sql)->queryAll();

                /*
				if($type == 5)
				foreach($paymethods as $key => $item) {
					if($item['config_name'] == "Bank") {
						 unset($paymethods[$key]);
					}
				}
			    */

                // generate payment id
                $payment = new Payments();
				
                $payment->setScenario('generate');
                $payment->user_id = $this->user->ID;
                $payment->amount = $payform->ik_payment_amount;
                $payment->currency = $curID;
                $payment->target_type = (count($a)>1 ? 1 : 0);
                $payment->target_id = (count($a)>1 ? $this->user->transitID : $payform->target);
                $payment->status = 3;
				
                $payment->save();


                $payform->ik_payment_id = $payment->id;
                $payform->ik_shop_id = isset($shop->shop_id) ? $shop->shop_id : NULL;
                $payform->ik_payment_desc = "Refill account of {$this->user['email']} - {$payform->target} for {$payform->ik_payment_amount}";
                $this->render('cashinsubmit', array(
                    'paymethods'=>$paymethods,
                    'payform'=>$payform,
                    'payment'=>$payment,
                ));

            }
	}

	function actionResult($system, $method = null)
	{
		Yii::log('PAYMENT actionResult', 'info', 'payment.'.$system);
		$merchant = MerchantFabric::buildMerchant($system, $method);
		$merchant->processResponse();
	}

	function actionSuccess($system, $method = null)
	{
		
		$model = new PayLog();
		
		/*ob_start ();
		echo 'liqpay testrequest';
		print_r($_REQUEST);
		$content = ob_get_contents();
		$model->content = $content;
		$model->save();		
		ob_end_clean ();*/
		
	    $merchant = MerchantFabric::buildMerchant($system, $method);
	    $merchant->createSuccess();

		if(isset($_REQUEST['payment'])) {
		   $request = explode('&', $_REQUEST['payment']);
			$tmp_arr = null;
			foreach ( $request as &$items){
				$tmp = explode('=', $items);
				$tmp_arr[$tmp[0]] = $tmp[1];
			}
		
			if(isset($tmp_arr['state']) && $tmp_arr['state'] == 'ok') {
				$this->render('success', array());
			} else {
				$this->render('fail', array(
				
				        ));
			}
		} else {
			$this->render('success', array());
		}  
	}

	function actionTest()
	{
		$merchant = MerchantFabric::buildMerchant('webmoney', 'wmu');
		echo $merchant->test();
	}

	function actionGetForm()
	{

		$payment 	= Payments::model()->findByPk($_POST['paymentId']);
		$payMethod 	= PayMethods::model()->findByAttributes(array('config_name'=>$_POST['methodName']));
		$paySystem 	= PaySystems::model()->findByPk($payMethod->system_id);
		
		
		$merchant = MerchantFabric::buildMerchant($paySystem->config_name, $payMethod->config_name);

		$payment->setScenario('update_system');
		$payment->paysystem_id =  $paySystem->id;
		$payment->paymethod_id =  $payMethod->id;
		$payment->save();

		$fields = $merchant->createForm($payment);
		
		if($merchant->config['submit_url'] == '/profile/TransitBank') {
			$merchant->config['submit_url'] = '/'.$_GET['language'].'/profile/TransitBank';
		}		
		
		$response = array(
			'submit_url'	=> $merchant->config['submit_url'],
			'fields' 		=> $fields,
		);

		echo json_encode($response);
	}

	function actionKvitancia() {
       $this->renderPartial('kvitancia', $_POST);
	}

	function actionEasyPay()
	{

		ob_start ();
		echo 'Terminal';
		print_r($_POST);
		$content = ob_get_contents();
		ob_end_clean ();

		$model = new PayLog();
		$model->content = $content;
		$model->save();

		$xml =	'<?xml version=\"1.0\" encoding=\"UTF-8\"?>';
		$xml.=	'<Response>';
		$xml.=		'<StatusCode>0</StatusCode>';
		$xml.=		'<StatusDetail>OK</StatusDetail>';
		$xml.= 		'<DateTime>'.date('Y-m-d\TH:i:s', time()).'</DateTime>';
		$xml.= 		'<Sign></Sign>';
		$xml.= 		'<AccountInfo>';
		$xml.= 			'<Parameter1>string</Parameter1>';
		$xml.= 			'<ParameterN>string</ParameterN>';
		$xml.= 		'</AccountInfo>';
		$xml.= '</Response>';

		echo $xml;
	}

	
	function actionQiwi()
	{
		
		//echo phpinfo();
		//die();

		/*$user 	= 9181234567;
        $amount = 100;
        $comment= 'Test pay';
        $txn 	= 123;
        $return = Yii::app()->ishop->createBill($user, $amount, $comment, $txn);
        echo $return;*/
		
		$user = 9181234567;
        $amount = 100;
        $comment = 'Test pay';
        $txn = 123;
		//require_once('protected/extensions/ishop/IShop.php');
        //$ishop = new IShop();
        $return = Yii::app()->ishop->createBill($user, $amount, $comment, $txn);
        echo $return;
		
		$this->payment = $payment;
		
		$user 	  = Users::model()->findByPk(Yii::app()->user->id);
		$currency = Currencies::model()->findByPk($this->payment->currency);
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
		
		$result = file_get_contents("http://www.mobw.ru/term2/xmlutf.jsp", FALSE, $context);
	}

	function actionFail()
	{
        $this->render('fail', array(

        ));
	}

}