<?php


class PartnerController extends Controller {
	public $filter;

	public function __construct($id, $module = null) {
        $this->layout = 'partner';
        parent::__construct($id, $module);

        if($this->user->partner != 2){
            $this->redirect(YII::app()->createUrl('/index'));
        }
    }

    protected function getPartnerAccount() {
        return Users::getPartnerAccount(Yii::app()->user->getId());
    }

    public function actionIndex() {
        $this->filter = 'index';

        $account = $this->getPartnerAccount();
        if (!$account) {
            return $this->redirect('new');
        }

        $mtdata = null;

        if ($account) {
            $mt = new MTconnector();
            if ($mt->connected()){
                $mtdata = $mt->find($account->mtID);
            }
        }

        $this->render('index', array(
            'account' => $account,
            'mtdata' => $mtdata,
        ));
    }

    public function actionNew() {
        $this->filter = 'index';
        $account = $this->getPartnerAccount();
        if ($account) {
            return $this->redirect('index');
        }

        $partnerAccType = Fxtypes::model()->find(array('condition'=>'mtGroup = \'Partner\''));

        if (isset($_POST['leverage']) && $this->user->status == 1 && $this->user->partner == 2){
            $leverage = intval($_POST['leverage']);

            if ($partnerAccType && $leverage){
                $result = false;

                $user = Users::model()->findByPk($this->user->ID);
                $mt = new MTconnector();

                if($mt->connected()) {
                    /// пароль к счёту
                    $password = Users::generatePassword();
                    /// инвесторский пароль
                    $investPassword = Users::generatePassword();

                    $country = Countries::model()->findByPk($user->country);

                    $agent_account = intval($user->agent_account);
                    if (!$agent_account && $user->partneraccount) {
                        $agent = Users::getPartnerAccountByTransitID($user->partneraccount);
                        if ($agent) {
                            $agent_account = intval($agent->mtID);
                        }
                    }

                    $db = Yii::app()->db->beginTransaction();
                    try {
                        $mtuser = array(
                            'login' => 0, // login
                            'group' => $partnerAccType->mtGroup, // group
                            'password' => $password, // password
                            'password_investor' => $investPassword,
                            'enable' => 1, // enable
                            'enable_change_password' => 1, // allow to change password
                            'enable_read_only' => 0, // allow to open/positions (TRUE-may not trade)
                            'zipcode' => $user->transitID,
                            'password_phone' => iconv("UTF-8", "cp1251", $user->phonePassword), // phone password
                            'name' => iconv("UTF-8", "cp1251", "{$user->familyName} {$user->givenName} {$user->middleName}"), // name
                            'country' => $country->eng, // country
                            'city' => iconv("UTF-8", "cp1251", $user->city), // city
                            'state' => "", // state
                            'address' => iconv("UTF-8", "cp1251", $user->address), // address
                            'phone' => iconv("UTF-8", "cp1251", $user->phone), // phone
                            'email' => iconv("UTF-8", "cp1251", $user->email), // email
                            'comment' => "Trade account", // comment
                            'leverage' => $leverage, // leverage
                            'agent_account' => $agent_account ? $agent_account : intval($user->partneraccount)
                        );

                        $tradeAccount = Tradeaccounts::model()->findByAttributes(array('userID'=>$user->ID, 'fxType'=>$partnerAccType->ID));
                        if (!$tradeAccount) {
                            $tradeAccount = new Tradeaccounts();
                        }
                        $tradeAccount->userID = $user->ID;
                        $tradeAccount->leverage = $leverage;
                        $tradeAccount->fxType = $partnerAccType->ID;
                        $tradeAccount->password = md5($password);

                        //$user->partner_mtID = $tradeAccount->mtID = $mtid = $mt->recordNew($mtuser);
                        $tradeAccount->mtID = $mtid = $mt->recordNew($mtuser);

                        if (!$mtid) {
                            throw new Exception('Partner Account not found');
                        }

                        $result = $tradeAccount->save();

                        $db->commit();
                    } catch (Exception $e) {
                        $db->rollback();
                        throw $e;
                    }

                    if ($result) {
                        ///письмо
                        $userLang = $user->language ? $user->language : $country->langID;
                        $lang =  Languages::model()->findByAttributes(array('active' => 1, 'id' => $userLang),array('order'=>'iso'));
                        $params = array (
                            'firstName'=>$user->givenName,
                            'middleName'=>$user->middleName,
                            'mtid'=>$mtid,
                            'password'=>$password,
                            'language'=>isset($_GET['language']) ? $_GET['language']: ($lang ? $lang->iso : 'en'),
                            'password_investor'=>$investPassword
                        );

                        Mail::send('new_partner', $params, $user->email);

                        Yii::app()->user->setFlash('success', array(
                            'header'=>Yii::t('partner', 'SUCCESS_HEADER'),
                            'text'=>Yii::t('partner', 'SUCCESS_TEXT')));
                    }

                    return $this->redirect('index');
                }

                if (!$result) {
                    Yii::app()->user->setFlash('error', array(
                        'header'=>Yii::t('alert', "Ошибка связи с сервером"),
                        'text'=>Yii::t('alert', "Пожалуйста, попробуйте еще раз через некоторое время.")));
                }
            }
        }

        $this->render('new',
            array(
                'partnerAccType'=>$partnerAccType,
            )
        );
    }

    public function actionProfile() {
        $this->filter = 'profile';

        $account = $this->getPartnerAccount();

        $this->render('profile',
            array(
                'refID' => $account ? $account->mtID : $this->user->transitID
            )
        );
    }

    public function actionClients() {
        $this->filter = 'clients';

        $partnerAccount = $this->getPartnerAccount();

        $connection=Yii::app()->db;

        if ($partnerAccount) {
            $sql = "SELECT DISTINCT t.mtID, u.givenName, u.middleName, u.familyName FROM `tradeaccounts` t
                    LEFT JOIN users u ON u.id = t.userID
                    WHERE (u.agent_account = '{$partnerAccount->mtID}' OR u.partneraccount = '{$this->user->transitID}')";
        } else {
            $sql = "SELECT DISTINCT t.mtID, u.givenName, u.middleName, u.familyName FROM `tradeaccounts` t
                    LEFT JOIN users u ON u.id = t.userID
                    WHERE u.partneraccount = '{$this->user->transitID}'";
        }

        $allusers=$connection->createCommand($sql)->queryAll();

        $request = array();
        $user = array();

        foreach ($allusers as $val) {
            $request[] = intval($val['mtID']);
            $user[$val['mtID']] = array('givenName' => $val['givenName'], 'middleName' => $val['middleName'] , 'familyName' => $val['familyName']);
        }

        $clients = array();

        if (isset($request) && count($request) > 0){
            $mt = new MTconnector();
            if($mt->connected()) {
                $mt4acc = $mt->find($request);
                foreach ($mt4acc as &$items){
                    if (($partnerAccount && $items['agent_account'] == $partnerAccount->mtID) || $items['agent_account'] == $this->user->transitID){
                        $temp = array();
                        $temp['item'] = $items;
                        $temp['user'] = $user[$items['login']];
                        $clients[] = $temp;
                    }
                }
            }
        }

        $this->render('partner', array(
            'clients'=>$clients
        ));
	}
	 
    public function actionStatistic() {
        $this->filter = "statistic";

        $data = array();
        $footerData = array();
        $info = array();

        $model = new TradeHistoryForm();

        $partnerAccount = $this->getPartnerAccount();
        $accounts = array();
        if ($partnerAccount) {
            $accounts[$partnerAccount->mtID] = $partnerAccount->fxType_->name . ': '.$partnerAccount->mtID;
            $model->account = $partnerAccount->mtID;
        }

        if (isset($_POST['TradeHistoryForm'])) {
            $mq = new MQApi();
            $model->setMQApi($mq);
            $model->attributes = $_POST['TradeHistoryForm'];
            if (!$model->password) {
                $mt = new MTconnector();
                if ($mt->connected()) {
                    $mtdata = $mt->find($partnerAccount->mtID);
                    if ($mtdata) {
                        $model->password = $mtdata['password_investor'];
                    }
                }
            }
            if ($model->validate()) {
                $data = $mq->getHistory($model->fromDate, $model->toDate);
                if ($data) {
                    $footerData = array(
                        array(
                            'colspan'=>12,
                            'value' => sprintf('Profit/Loss: %s
                                                Credit: %s
                                                Deposit: %s
                                                Withdrawal: %s',
                                $data['profit_loss'],
                                $data['credit'],
                                $data['deposit'],
                                $data['withdrawal']
                            )
                        ),
                        $data['profit']
                    );
                    $data = $data['data'];
                }
                $info = $mq->getInfo();
            } else {
                Yii::app()->user->setFlash('error', array(
                    'header'=>Yii::t('alert', "Необходимо исправить следующие ошибки:"),
                    'text'=>CHtml::errorSummary($model, '','')
                ));
            }
        } else {
            $now = time();
            $from = strtotime('-1 month');
            $model->fromDate = $from;
            $model->toDate = $now;
            $model->fromDateTxt = date('j.m.Y', $from);
            $model->toDateTxt = date('j.m.Y', $now);
        }

        $dataProvider = new CArrayDataProvider($data, array(
            'keyField' => 'order',
            'pagination' => false
        ));

        $params = array(
            'model' => $model,
            'accounts' => $accounts,
            'data' => $dataProvider,
            'footerData' => $footerData,
            'info' => $info,
        );

        if (isset($_GET['ajax'])) {
            $this->renderPartial('history', $params);
            return;
        }

        $this->render('stat', $params);
    }

    public function actionReplenish() {
        $this->filter = 'replenish';

        $partnerAccount = $this->getPartnerAccount();

        if (!$this->user->replenish_client) {
            Yii::app()->user->setFlash('notice',
                array(
                    'header'=>Yii::t('partner', 'REPLENISH_DENY'),
                    'text'=>Yii::t('partner', 'REPLENISH_DENY_TEXT'),
                )
            );
        } else {
            $transfer = new PartnerTransferForm();
            $transfer->amount = '';
            $transfer->password = '';
            $request = array();
            if ($partnerAccount) {
                $request[] = $partnerAccount->mtID;
                $transfer->source = $partnerAccount->mtID;

                $targetAccounts = Transitaccounts::model()->with('user_')->findAll(array(
                    'condition'=>"user_.agent_account='{$partnerAccount->mtID}' OR user_.partneraccount='{$this->user->transitID}' AND currency='840'",
                ));
            } else {
                $targetAccounts = Transitaccounts::model()->with('user_')->findAll(array(
                    'condition'=>"user_.partneraccount='{$this->user->transitID}' AND currency='840'",
                ));
            }

            foreach ($targetAccounts as $val) {
                $request[] = intval($val->user_->transitID);
            }

            $mtdata = array();
            if ($request) {
                $mt = new MTconnector();
                if($mt->connected()) {
                    $mt4accounts = $mt->find($request);
                    foreach ($mt4accounts as $val){
                        $mtdata[$val['login']]['leverage'] = $val['leverage'];
                        $mtdata[$val['login']]['balance'] = $val['balance'];
                    }
                }
            }

            if (isset($_POST['PartnerTransferForm'])){
                $transfer->attributes = $_POST['PartnerTransferForm'];
                if ($transfer->validate()) {
                    $transfer->completeTransfer();
                    $this->redirect('replenish');
                    return;
                }
                $transfer->password = '';
            };

            $this->render('replenish', array(
                'partnerAccount' => $partnerAccount,
                'transfer' => $transfer,
                'mtdata' => $mtdata,
                'targetAccounts' => $targetAccounts,
            ));

            return;
        }

        $this->render('replenish', array(
            'partnerAccount' => $partnerAccount,
        ));
    }

    public function actionReplenishRequest() {

        $account = $this->getPartnerAccount();

        if (@$_POST['request'] && !$this->user->replenish_client && $this->user->partner) {

            $subject = 'Заявка на внутренний перевод';
            $text = 'Имя пользователя: ' . $this->user->familyName . ' ' .  $this->user->givenName . ' ' . $this->user->middleName . '<br>';
            $text .= 'ID пользователя - '.$this->user->ID."<br />";
            $text .= 'Email - '.$this->user->email;
            $to = "support@fx-private.com";
            $headers = 'From: support@fx-private.com' . "\r\n" .
                'Content-type: text/html; charset="utf-8"'. "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $text, $headers);

            Yii::app()->user->setFlash('success', array(
                'header'=>Yii::t('alert', 'Ваша заявка успешно принята'),
                'text'=>Yii::t('partner', 'REPLENISH_SUBMITTED')));
        }

        $this->redirect('replenish');
    }
}
