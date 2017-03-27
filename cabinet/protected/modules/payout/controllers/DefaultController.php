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

    public function filters() {
        return [
            'accessControl',
        ];
    }

    public function actions() {
        return [
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => [
                'class'     => 'CCaptchaAction',
                'backColor' => 0x141517,
                'maxLength' => '7',
                'minLength' => '7',
                'width'     => '110',
                'height'    => '35',
                'foreColor' => 0xC7C7C7,
                'testLimit' => 0
            ],
        ];
    }

    public function accessRules() {
        return [
            [
                'deny',
                'actions' => ['index', 'credentials', 'fail'],
                'users'   => ['?'],
            ],
            [
                'allow',
                'actions' => ['status'],
                'users'   => ['*'],
            ],
        ];
    }

    public function actionIndex() {
        $tradeaccounts = null;
        $transits = null;
        $mtdata = [];
        $mt = new MTconnector();
        $errors = [];
        if (isset($_POST['PayoutForm'])) {
            $connection = Yii::app()->db;
            $payoutcredentials = Payoutcredentials::model()->with('payoutmethods_')->findByPK($_POST['PayoutForm']['method']);
            if (isset($_POST['payment'])) {
                $acc = explode('-', $_POST['payment']);
                if (!isset($acc[1])) {
                    $sql = "SELECT * FROM tradeaccounts WHERE mtID = '" . $_POST['payment'] . "'";
                    $trac = $connection->createCommand($sql)->queryRow();
                    $curID = 840;
                    //$mt = new MTconnector('78.140.130.240:443', 11, 'qwerty1');
                    $mqApi = Yii::app()->params['mqApi'];
                    $mt = new MTconnector($mqApi['host'] . '.' . $mqApi['port'], $mqApi['login'], $mqApi['password']);
                    if ($mt->connected()) {
                        $mt4accounts = $mt->find($trac['mtID']);
                        $trac['amount'] = $mt4accounts['balance'];
                    }
                } else {
                    $sql = "SELECT * FROM transitaccounts WHERE userID = '" . $this->user->ID . "' AND currency = '" . $acc[1] . "'";
                    $trac = $connection->createCommand($sql)->queryRow();
                    $curID = $acc[1];
                }
                if ($trac['amount'] < $_POST['payout']['payout_amount'] || $_POST['payout']['payout_amount'] <= 0) {
                    Yii::app()->user->setFlash('error', [
                        'header' => Yii::t('payout', "Ошибка"),
                        'text'   => Yii::t('payout', "Недостаточно средств на счете")
                    ]);
                } else {
                    $subject = "Вывод средств";
                    $to = "transactions@fx-private.com";
                    $fio = "ФИО: " . $this->user->givenName . " " . $this->user->middleName . " " . $this->user->familyName . "<br>";
                    $text = "Номер счета: " . $_POST['payment'] . "<br>";
                    $text .= "Сумма: " . addslashes($_POST['payout']['payout_amount']) . "<br>";
                    $text .= "Способ вывода:" . $payoutcredentials->payoutmethods_['name'] . "<br>";
                    $text .= "Реквизиты:" . $payoutcredentials['accountnumber'] . "<br>";
                    $text .= "Платежный пароль:" . (isset($_POST['payout']['payout_pass']) ?
                            base64_encode(pack('H*', sha1($_POST['payout']['payout_pass']))) : '') . "<br>";
                    $text .= "Email:" . $this->user->email . "<br>";
                    $from = 'support';
                    $payment_source_id = explode('-', $_POST['payment']);
                    if (isset($payment_source_id[1])) {
                        $payment_source_id = $this->user->transitID;
                    } else {
                        $payment_source_id = $payment_source_id[0];
                    }
                    $transfer = new Transfers();
                    $transfer->date = new CDbExpression('now()');
                    $transfer->type = $payoutcredentials->payoutmethods_->config_name == 'fxpartner' ? 9 : 6;
                    $transfer->issuer = $this->user->ID;
                    $transfer->sourceID = $payment_source_id;
                    //var_dump($_POST['payment']);
                    if ($payoutcredentials->payoutmethods_->config_name == 'fxpartner') {
                        $transfer->targetID = $payoutcredentials->accountnumber;
                    }
                    $transfer->currency = $curID;
                    $transfer->amount = $_POST['payout']['payout_amount'];
                    $transfer->status = 1;
                    //password
                    $transfer->pass = @$_POST['payout']['payout_pass'];
                    $transfer->add_info = $text;
                    $transfer->setScenario('payout');
                    if ($transfer->validate()) {
                        $transfer->save();
                        Yii::app()->user->setFlash('notify', [
                            'header' => Yii::t('alert', "Ваша заявка успешно принята"),
                            'text'   => Yii::t('alert', "Отслеживать исполнение заявки Вы сможете в Личном кабинете в разделе") . " <a href='"
                                . $this->createUrl('/account/paymenthistory') . "'>" . Yii::t('alert', "История платежей") . "</a>"
                        ]);
                        $headers = 'From: support@fx-private.com' . "\r\n" .
                            'Reply-To: support@fx-private.com' . "\r\n" .
                            'Content-type: text/html; charset="utf-8"' . "\r\n" .
                            'X-Mailer: PHP/' . phpversion();
                        mail($to, $subject, $fio . $text, $headers);
                    } else {
                        Yii::app()->user->setFlash('error', [
                            'header' => Yii::t('payout', "Ошибка"),
                            'text'   => CHtml::errorSummary($transfer, '', '')
                        ]);
                    }
                }
            }
            if (!isset($payoutcredentials->status)) {
                Yii::app()->user->setFlash('error', [
                    'header' => Yii::t('payout', "Ошибка"),
                    'text'   => Yii::t('payout', "Платежная система не выбрана")
                ]);
            } else {
                if ($payoutcredentials->status != 1) {
                    unset($_POST['PayoutForm']);
                    Yii::app()->user->setFlash('error', [
                        'header' => Yii::t('payout', "Ошибка"),
                        'text'   => Yii::t('payout', "Выбранная платежная система не аттестована")
                    ]);
                } else {
                    $sql = "SELECT t.*, f.name FROM tradeaccounts t RIGHT JOIN payoutmethod_allow_fxtype paf ON paf.fxtype_id = t.fxType LEFT JOIN fxtypes f ON f.id = t.fxType WHERE t.userID = '"
                        . $this->user->ID . "' AND paf.method_id = '" . $payoutcredentials->payoutmethodID . "'";
                    $tradeaccounts = $connection->createCommand($sql)->queryAll();
                    $sql = "SELECT t.*, c.alphaCode FROM transitaccounts t RIGHT JOIN payoutmethod_allow_curr pac ON pac.curr_id = t.currency LEFT JOIN currencies c ON c.curID = t.currency WHERE t.userID = '"
                        . $this->user->ID . "' AND pac.method_id = '" . $payoutcredentials->payoutmethodID . "'";
                    $transits = $connection->createCommand($sql)->queryAll();
                    $request = [];
                    foreach ($tradeaccounts as $key => $val) {
                        $request[] = intval($val['mtID']);
                    }
                    if ($mt->connected()) {
                        if (!empty ($request)) {
                            $mt4accounts = $mt->find($request);
                            foreach ($mt4accounts as $key => $val) {
                                $mtdata[$val['login']]['leverage'] = $val['leverage'];
                                $mtdata[$val['login']]['balance'] = $val['balance'];
                                /*                              TODO: Добавить сохранение данных в кеш!!!
                                                            Tradeaccounts::model()-> */
                            }
                        }
                        $raterequest = [];
                        foreach ($transits as $k0 => $v0) {
                            $curlist[$v0['alphaCode']] = $v0['alphaCode'];
                            $raterequest[] = $v0['alphaCode'];
                        }
                        $rates = $mt->rates($raterequest);
                    }
                }
            }
        } elseif ($this->user->agent_account || $this->user->partneraccount) {
            $partnerCred = $this->user->payoutcredentials_(['with' => 'payoutmethods_', 'condition' => 'payoutmethods_.config_name=\'fxpartner\'']);
            if (!$partnerCred && Transfers::model()->find(['condition' => "type = 8 AND issuer = '{$this->user->ID}'"])
                && ($partner = $this->user->agent_account ? Users::getAgentAccount($this->user->agent_account) :
                    Users::getPartnerAccountByTransitID($this->user->partneraccount))
                && ($method = Methods::model()->findByAttributes(['config_name' => 'fxpartner']))
            ) {
                // create new payout method FXPartner
                $model = new Payoutcredentials();
                $model->userID = $this->user->ID;
                $model->payoutmethodID = $method->ID;
                $model->accountnumber = $partner->mtID;
                $model->papers = 0;
                $model->status = 1;
                $model->save(false);
            }
        }
        //echo "<pre>"; print_r($this->user); exit();
        $credentials = $this->user->payoutcredentials_(['with' => 'payoutmethods_']);
        if (count($credentials) > 0) {
            $payoutform = new PayoutForm();
            $showPaymentPass = !$this->user->ignorePaymentPass;
            $this->render('index', [
                'credentials' => $credentials, 'payoutform' => $payoutform, 'tradeaccounts' => $tradeaccounts, 'transits' => $transits,
                'mtdata'      => $mtdata, 'errors' => $errors, 'showPaymentPass' => $showPaymentPass
            ]);
        } // Если у человека нет платежных систем - покажем ему соответствующее сообщение:
        else {
            $this->render('nocredentials');
        }
    }

    private function xmail($from, $to, $subj, $text, $filename) {
        $un = strtoupper(uniqid(time()));
        $head = "From: $from\n";
        $head .= "To: $to\n";
        $head .= "Subject: $subj\n";
        $head .= "X-Mailer: PHPMail Tool\n";
        $head .= "Reply-To: $from\n";
        $head .= "Mime-Version: 1.0\n";
        $head .= "Content-Type:multipart/mixed;";
        $head .= "boundary=\"----------" . $un . "\"\n\n";
        $zag = "------------" . $un . "\nContent-Type:text/html;\n";
        $zag .= "Content-Transfer-Encoding: 8bit\n\n$text\n\n";
        $zag .= "------------" . $un . "\n";
        foreach ($filename as $flnm) {
            $f = fopen($flnm, "rb");
            $zag .= "Content-Type: application/octet-stream;";
            $zag .= "name=\"" . basename($flnm) . "\"\n";
            $zag .= "Content-Transfer-Encoding:base64\n";
            $zag .= "Content-Disposition:attachment;";
            $zag .= "filename=\"" . basename($flnm) . "\"\n\n";
            $zag .= chunk_split(base64_encode(fread($f, filesize($flnm)))) . "\n";
        }

        return @mail("$to", "$subj", $zag, $head);
    }

    public function actionNewcredentials() {
        $model = new Payoutcredentials;
        $model->userID = Yii::app()->user->getId();
        $payoutmethods = Payoutmethods::model()->findAll([
            'condition' => " enabled = 1 AND config_name != 'fxpartner' AND id NOT IN(SELECT payoutmethodID FROM payoutcredentials WHERE userID = '"
                . $model->userID . "' AND status = '1')",
        ]);
        if (isset($_POST['Payoutcredentials'])) {
            $_POST['Payoutcredentials']['date'] = time();
            $model->attributes = $_POST['Payoutcredentials'];
            $model->userID = $this->user->ID;
            $model->uploadPapers = CUploadedFile::getInstances($model, 'uploadPapers');
            if ($model->save()) {
                if (isset($model->uploadPapers) && count($model->uploadPapers) > 0) {
                    foreach ($model->uploadPapers as $key => $img) {
                        $rnd = dechex(rand() % 999999999);
                        $path[$key] = Yii::app()->params['dir_upload_user'] . $rnd . "." . $img->extensionName;
                        $img->saveAs($path[$key]);
                    }
                }
                $payout_method = methods::model()->findByPK($model->payoutmethodID);
                $subject = "Аттестация платежных реквизитов";
                $to = "transactions@fx-private.com";
                $text = "ФИО: " . $this->user->givenName . " " . $this->user->middleName . " " . $this->user->familyName . "<br>";
                $text .= "Название платежной системы: " . $payout_method->name . "<br>";
                $text .= "Email: " . $this->user->email . "<br>";
                $text .= "Номер кошелька клиента: " . $this->user->transitID . "<br>";
                $from = 'support';
                if (isset($payout_method->accountnumber)) {
                    $text .= "Номер счета в выбранной системе: " . $payout_method->accountnumber;
                }
                if ($model->uploadPapers) {
                    $this->xmail($from, $to, $subject, $text, $path);
                } else {
                    $headers = 'From: support@fx-private.com' . "\r\n" .
                        'Reply-To: support@fx-private.com' . "\r\n" .
                        'Content-type: text/html; charset="utf-8"' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    mail($to, $subject, $text, $headers);
                }
                Yii::app()->user->setFlash('success', [
                    'header' => Yii::t('payout', "Заявка на аттестацию счета отправлена"),
                    'text'   => Yii::t('payout', 'Вы будете уведомлены после рассмотрения заявки администатором.')
                ]);
                $this->redirect(['/payout']);
            } else {
                Yii::app()->user->setFlash('error', [
                    'header' => Yii::t('alert', "Необходимо исправить следующие ошибки:"),
                    'text'   => CHtml::errorSummary($model, '', '')
                ]);
            }
        }
        $this->render('newcredentials', [
            'model'         => $model,
            'payoutmethods' => $payoutmethods,
        ]);
    }
}