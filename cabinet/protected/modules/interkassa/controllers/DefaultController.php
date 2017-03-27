<?php

class DefaultController extends Controller
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
                'actions'=>array('index', 'success', 'fail', 'accessRules'),
                'users'=>array('?'),
            ),
            array('allow',
                'actions'=>array('status', 'accessRules'),
                'users'=>array('*'),
            ),
        );
    }

        private function checksignature($ik_details, $ik_secret_key) {
            // Эту валидацию будет правильнее перенести в модель
            $ik_sign_hash_str = $ik_details['ik_shop_id'] . ':' .
                    $ik_details['ik_payment_amount'] . ':' .
                    $ik_details['ik_payment_id'] . ':' .
                    $ik_details['ik_paysystem_alias'] . ':' .
                    $ik_details['ik_baggage_fields'] . ':' .
                    $ik_details['ik_payment_state'] . ':' .
                    $ik_details['ik_trans_id'] . ':' .
                    $ik_details['ik_currency_exch'] . ':' .
                    $ik_details['ik_fees_payer'] . ':' .
                    $ik_secret_key;
            return (strtoupper($ik_details['ik_sign_hash']) == strtoupper(md5($ik_sign_hash_str)));
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
/*                              TODO: Добавить сохранение данных в кеш!!!
                                Tradeaccounts::model()-> */
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
            }
            else {
                $payform->setScenario('intersubmit');
                // submit to interkassa requires:
                // ik_shop_id, ik_payment_amount, ik_payment_id, ik_payment_desc, ik_paysystem_alias
                // shop_id is stored in our db,
                // amount is already entered
                // payment id and description will be generated after creating a log record
                // paysystem_alias will be requested from user in view file
                //
                // get settings for IK:
                $a = explode('-', $payform->target);
                if (count($a)>1) {
                    $curID = $a[1];
                    $shop = IkShops::model()->find('cur_id = "' . $curID .  '"');
                    $type = 4;
                } else {
                    $curID = 840;
                    $shop = IkShops::model()->find('cur_id = "840"');
                    $type = 5;
                }
                

                //$paysystems = explode(';',$shop->paysystems);
                $paysystems = unserialize($shop->paysystems);
                // generate payment id
                $log = new Transfers();
                $log->issuer = $this->user->ID;
                $log->date = new CDbExpression('now()');
                $log->amount = $payform->ik_payment_amount;
                $log->actualamount = 0;
                $log->comission = 0;
                $log->currency = $curID;
                $log->sourceID = 0;
                $log->targetID = (count($a)>1 ? $this->user->transitID : $payform->target);
                $log->status = 3;
                $log->type = $type;
                $log->save();
                $payform->ik_payment_id = $log->ID;
                $payform->ik_shop_id = $shop->shop_id;
                $payform->ik_payment_desc = "Refill account of {$this->user['email']} - {$payform->target} for {$payform->ik_payment_amount}";
                if($type == 4) $paysystems['bank'] = "Банковский перевод";
                $this->render('cashinsubmit', array(
                    'paysystems'=>$paysystems,
                    'payform'=>$payform,
                ));

            }
	}

        public function actionSuccess() {
            if(isset($_POST['ik_shop_id'])){
                $shop = IkShops::model()->findByAttributes(array('shop_id' => $_POST['ik_shop_id']));
                if (empty($shop)) $this->redirect(Yii::app()->createUrl ('interkassa/default/fail'));
                $ik_details = array(
                    'ik_shop_id' => $_POST['ik_shop_id'],
                    'ik_payment_id' => $_POST['ik_payment_id'],
                    'ik_paysystem_alias' => $_POST['ik_paysystem_alias'],
                    'ik_baggage_fields' => $_POST['ik_baggage_fields'],
                    'ik_payment_state' => $_POST['ik_payment_state'],
                    'ik_trans_id' => $_POST['ik_trans_id'],
                );
                // Найдем платеж:
                    $log = Transfers::model()->findByPk($ik_details['ik_payment_id']);
                    if (empty($log)) $this->redirect(Yii::app()->createUrl ('interkassa/default/fail'));
                    // Если статус платежа еще не установлен на OK - поставим статус Pending:
                    if ($log->status != 0) {
                        if (($log->issuer == $this->user->ID) && ($ik_details['ik_payment_state']!='fail')){
                            $log->status = 1;
                            $log->save();
                        }
                    }
            }
            $this->render('success');
        }

        public function actionStatus(){
            if(isset($_POST['ik_shop_id'])){
                $iklog = new IkLog();
                $shop = IkShops::model()->findByAttributes(array('shop_id' => $_POST['ik_shop_id']));
                if (empty($shop)) Yii::app()->end();
                $ik_details = array(
                    'ik_shop_id' => $_POST['ik_shop_id'],
                    'ik_payment_amount' => $_POST['ik_payment_amount'],
                    'ik_payment_id' => $_POST['ik_payment_id'],
                    'ik_payment_desc' => $_POST['ik_payment_desc'],
                    'ik_paysystem_alias' => $_POST['ik_paysystem_alias'],
                    'ik_baggage_fields' => $_POST['ik_baggage_fields'],
                    'ik_payment_state' => $_POST['ik_payment_state'],
                    'ik_trans_id' => $_POST['ik_trans_id'],
                    'ik_currency_exch' => $_POST['ik_currency_exch'],
                    'ik_fees_payer' => $_POST['ik_fees_payer'],
                    'ik_sign_hash' => $_POST['ik_sign_hash'],
                );
                $iklog->attributes = $ik_details;
                if ($this->checksignature($ik_details, $shop->secret_key)) {
                // Найдем платеж:
                $log = Transfers::model()->findByPk($ik_details['ik_payment_id']);
                if (!empty($log)) {
                    if ($ik_details['ik_payment_state'] == 'fail') {
                        //Если пришло уведомление о неудачном платеже - запишем статус в БД:
                        $log->status = 2;
                        $log->save();
                        $iklog->process_status = 'Notification about failed transaction. aborting';
                        $iklog->save();
                        Yii::app()->end();
                    } else
                    // Если статус платежа еще не установлен на OK - проведем платеж:
                    if ($log->status != 0) {
                        if (($ik_details['ik_payment_amount'] == round(($log->amount), 2)) &&
                            ($ik_details['ik_payment_amount'] > 0)
                        ) {
                            //$mt = new MTconnector('78.140.130.240:443', 11, 'qwerty1');
                            $mqApi = Yii::app()->params['mqApi'];
                            $mt = new MTconnector($mqApi['host'] . '.' . $mqApi['port'], $mqApi['login'], $mqApi['password']);
                            if($mt->connected())
                                { 
                                if (
                                $mt->transaction(
                                    intval($log->targetID),
                                    floatval($ik_details['ik_payment_amount']),
                                    "Dout-{$ik_details['ik_payment_amount']}:{$log->targetID}"
                                )) 
                                    {
                                    if ($log->type == 4) {
                                        $transit = Transitaccounts::model()->find(array(
                                            'condition'=>'userID='.$log->issuer .
                                            ' AND currency="' . $log->currency . '"'
                                            ));
                                        $transit->amount = number_format(
                                                ($transit->amount +
                                                $ik_details['ik_payment_amount']),
                                                6, '.', '');
                                        $transit->save();
                                    }
                                    $log->status = 0;
                                    $log->save();
                                    $iklog->process_status = "All checks passed and transactions completed";
                                    $iklog->save();
                                } else {
                                    $log->status = 2;
                                    $log->save();
                                    $iklog->process_status = "Connected to mt4 server, though transaction failed.";
                                    $iklog->save();
                                    throw new CHttpException(400,'Connected to transaction server, though transaction failed.');
                                }
                            } else {
                                $log->status = 2;
                                $log->save();
                                $iklog->process_status = "Could not connect to MT4";
                                $iklog->save();
                                throw new CHttpException(400,'Could not connect to transaction server.');
                            }
                        } else $iklog->process_status = "incorrect payment amount";
                    } else $iklog->process_status = "status already set to OK";
                } else $iklog->process_status = "log record not found";
            } else $iklog->process_status = "not passed signature validation";
            $iklog->save();
            }
        }

    public function actionFail() {
            if(isset($_POST['ik_shop_id'])){
                //echo "POST found</br>";
                $shop = IkShops::model()->findByAttributes(array('shop_id' => $_POST['ik_shop_id']));
                if (!empty($shop)) {
                    //echo "Shop found</br>";
                    $ik_details = array(
                        'ik_shop_id' => $_POST['ik_shop_id'],
                        'ik_payment_id' => $_POST['ik_payment_id'],
                        'ik_paysystem_alias' => $_POST['ik_paysystem_alias'],
                        'ik_baggage_fields' => $_POST['ik_baggage_fields'],
                        'ik_payment_state' => $_POST['ik_payment_state'],
                        'ik_trans_id' => $_POST['ik_trans_id'],
                    );
                    $log = Transfers::model()->findByPk($ik_details['ik_payment_id']);
                    if (!empty($log))
                    // Если статус платежа еще не установлен на OK - поставим статус Pending:
                    if ($log->status != 0) {
                        //echo "Status not OK</br>";
                        //echo "log issuer is {$log->issuer} while current user is {$this->user->ID}<br/>";
                        //echo "ik payment state is {$ik_details['ik_payment_state']}";
                        if (($log->issuer == $this->user->ID) && ($ik_details['ik_payment_state']=='fail')){
                            //echo "Found user and payment state is fail</br>";
                            $log->status = 2;
                            $log->save();
                        }
                    }
                }
            }
            $this->render('fail');
        }
}