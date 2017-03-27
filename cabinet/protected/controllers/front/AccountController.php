<?php

class AccountController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public $notimer;

    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
    }

    public function actionNotify() {
        $model = new Notify();
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'Notify') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (isset($_POST['Notify'])) {
            $model->attributes = $_POST['Notify'];
            if ($model->validate()) {
                $path = false;
                $model->scan = CUploadedFile::getInstance($model, 'scan');
                if (isset($model->scan)) {
                    $rnd = dechex(rand() % 999999999);
                    $path = Yii::app()->params['dir_upload_user'] . $rnd . "." . $model->scan->extensionName;
                    $model->scan->saveAs($path);
                }
                $user = Users::model()->findByPk(Yii::app()->user->getId());
                $subject = "Уведомление о платеже";
                $from = $user->email;
                $to = "transactions@fx-private.com";
                $text = 'Имя пользователя: ' . $user->familyName . " " . $user->givenName . " " . $user->middleName . "<br>";
                $text .= 'Номер торгового счета или FxPrivate-кошелька: ' . $model->no . "<br>";
                $text .= 'Сумма платежа: ' . $model->sum . "<br>";
                $text .= 'Валюта платежа: ' . $model->val . "<br>";
                $text .= 'Дата платежа: ' . $model->date . "<br>";
                switch ($_POST['Notify']['method']) {
                    case 0:
                        $text .= 'Способ оплаты: Электронные деньги <br>';
                        break;
                    case 1:
                        $text .= 'Способ оплаты: Банковский перевод <br>';
                        break;
                    case 2:
                        $text .= 'Способ оплаты: Другой способ оплаты <br>';
                        break;
                }
                if ($_POST['Notify']['name']) {
                    $text .= 'Название: ' . $_POST['Notify']['name'] . ' <br>';
                }
                if ($path) {
                    $r = $this->xmail($from, $to, $subject, $text, $path);
                } else {
                    $headers = 'From: support@fx-private.com' . "\r\n" .
                        'Reply-To: support@fx-private.com' . "\r\n" .
                        'Content-type: text/html; charset="utf-8"' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    $r = mail($to, $subject, $text, $headers);
                }
                if ($r) {
                    Yii::app()->user->setFlash('notice acc', [
                        'header' => Yii::t('messages', "Ваше уведомление успешно отправлено"),
                        'text'   => ''
                    ]);
                    $this->redirect(Yii::app()->createUrl("account/index"));
                }
            } else {
                Yii::app()->user->setFlash('error', [
                    'header' => Yii::t('alert', "Необходимо исправить следующие ошибки:"),
                    'text'   => CHtml::errorSummary($model, '', '')
                ]);
            }
        }
        $model = new Notify();
        /*$tradeaccounts = Tradeaccounts::model()->with('fxType_')->findAll(
                    array('condition'=>'userID='.Yii::app()->user->getId())
                    );*/
        $transits = $this->user->transitaccounts_(['with' => 'currency_']);
        $tradeaccounts = $this->user->tradeaccounts_();
        $this->render('notify', ['model' => $model, 'tradeacc' => $tradeaccounts, 'transits' => $transits]);
    }

    private function xmail($from, $to, $subj, $text, $filename) {
        $f = fopen($filename, "rb");
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
        $zag .= "Content-Type: application/octet-stream;";
        $zag .= "name=\"" . basename($filename) . "\"\n";
        $zag .= "Content-Transfer-Encoding:base64\n";
        $zag .= "Content-Disposition:attachment;";
        $zag .= "filename=\"" . basename($filename) . "\"\n\n";
        $zag .= chunk_split(base64_encode(fread($f, filesize($filename)))) . "\n";

        return @mail("$to", "$subj", $zag, $head);
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
                'testLimit' => 1
            ],
            // page action renders "static" pages stored under 'protected/views/account/pages'
            // They can be accessed via: index.php?r=account/page&view=FileName
            'page'    => [
                'class'  => 'CViewAction',
                'layout' => 'faq'
            ],
        ];
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // Инициализируем переменные, которые не будут инициализированы при
        // отсутствии подключения к торговому серверу:
        $mtdata =
        $rates =
        $curlist = [];
        $ask = [];
        if (isset($_GET['testmail'])) {
            for ($i = 0; $i < 20; $i++) {
                $params = [
                    'firstName'         => "TEST " . $i . "",
                    'middleName'        => "dorofiy",
                    'mtid'              => "123",
                    'password'          => "123",
                    'language'          => "ru",
                    'password_investor' => "123"
                ];
                Mail::send('new', $params, "anna.dorofiy@gmail.com");
            }
        }
        $convert = new ConvertForm();
        $conv = false;
        if (isset($_POST['ConvertForm'])) {
            $convert->attributes = $_POST['ConvertForm'];
            $convert->convert();
            $conv = true;
        }
        $transits = Transitaccounts::model()->
        with(
            ['currency_',]
        )->
        findAll(
            ['condition' => 'userID=' . Yii::app()->user->getId()]
        );
        $tradeaccounts = Tradeaccounts::model()->with('fxType_')->findAll(
            ['condition' => 'userID=' . Yii::app()->user->getId()]
        );
        $tradeaccProvider = new CActiveDataProvider('Tradeaccounts', [
            'criteria' => [
                'condition' => "ID='{$this->user->ID}'"
            ]
        ]);
        foreach ($tradeaccounts as $key => $val) {
            $request[] = intval($val['mtID']);
        }
        $mt = new MTconnector();
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
                $curlist[$v0->currency_['alphaCode']] = $v0->currency_['alphaCode'];
                $raterequest[] = $v0->currency_['alphaCode'];
            }
            $rates = $mt->rates($raterequest, true, $ask);
        }
        if (isset($_GET['ajax'])) {
            if (!isset($data)) {
                $data = [];
            }
            foreach ($rates as $k => $v) {
                if ($k == 'IDRUSD' && isset($ask[$k])) {
                    $data[$k] = '
								<div class="curpair curr left">' . $k . '</div>
								<div class="val right" data-ask="' . (@$ask[$k]) . '" data-rate="' . $v . '">' . $ask[$k] . '</div>
							';
                    continue;
                }
                if ($k != 'EURRUB' && $k != 'RUBEUR') {
                    $data[$k] = '
								<div class="curpair curr left">' . $k . '</div>
								<div class="val right" data-ask="' . (@$ask[$k]) . '" data-rate="' . $v . '">' . $v . '</div>
							';
                }
            }
            $datadropdown = $curlist;
            echo CHtml::radioButtonList("cursel", '', $data, [
                'separator' => '',
                'template'  => '<div class="trow">{input}{label}</div>',
                'encode'    => false
            ]);
            exit();
        }
        $user = Users::model()->find("id=" . Yii::app()->user->id);
        if (!$user->status || $user->status == 0 || $user->status == 3) // Если счет не аттестован - добавим сообщение:
        {
            Yii::app()->user->setFlash('notice',
                [
                    'header' => Yii::t('alert', "Счет не прошел аттестацию"),
                    'text'   => Yii::t('alert', 'Для полноценного использования личного кабинета необходимо выполнить аттестацию аккаунта. При нажатии на ссылку будет отображена форма аттестации. <br/>')
                        . CHtml::link(Yii::t('alert', 'Начать аттестацию счета'), Yii::app()->createUrl('/profile/verification'))
                        . Yii::t('alert', '<br/> Вы так же можете перейти на страницу аттестации, перейдя по ссылке "личные данные".')
                ]
            );
        }
        $this->render('index', [
            'transitid' => $this->user->transitID,
            'tradeacc'  => $tradeaccounts,
            'mtdata'    => $mtdata,
            'transits'  => $transits,
            'convert'   => $convert,
            'conv'      => $conv,
            'rates'     => $rates,
            'ask'       => $ask,
            'curlist'   => $curlist
        ]);
    }

    public function actionEdit() {
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        $fxtypes = Fxtypes::model()->findAll();
        $tradeaccount = new Tradeaccounts();
        $type = Tradeaccounts::model()->with('fxType_')->find(
            ['condition' => 't.userID=' . Yii::app()->user->getId() . ' AND t.id = ' . $_GET['id'] . ' ']
        );
        if (isset($_POST['Tradeaccounts'])) {
            $tradeaccount = Tradeaccounts::model()->findByPk($_GET['id']);
            $tradeaccount->attributes = $_POST['Tradeaccounts'];
            $tradeaccount->verifyCode = @$_POST['Tradeaccounts']['verifyCode'];
            $tradeaccount->setScenario('secure');
            if ($tradeaccount->validate()) {
                $tradeaccount->save();
                $params = [
                    'firstName'  => $user->givenName,
                    'middleName' => $user->middleName,
                    'lastName'   => $user->familyName,
                    'mtid'       => @$type->attributes['mtID'],
                    'plecho'     => $_POST['Tradeaccounts']['leverage'],
                    'language'   => $_GET['language'],
                ];
                Mail::send('edit', $params, 'support@fx-private.com');
                Yii::app()->user->setFlash('notice acc', [
                    'header' => Yii::t('alert', "Cмена кредитного плеча!"),
                    'text'   => Yii::t('alert', 'Ваша заявка на смену кредитного плеча успешно отправлена.')
                ]);
                $this->redirect(Yii::app()->createUrl("account/index"));
            } else {
                $er = $tradeaccount->getErrors();
            }
        }
        $this->render('edit', ['model' => $tradeaccount, 'fxtypes' => $fxtypes, 'type' => $type]);
    }

    public function actionNew() {
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        $fxtypes = Fxtypes::model()->findAll(['condition' => 'mtGroup != \'Partner\'']);
        $tradeaccount = new Tradeaccounts();
        if (isset($_POST['Tradeaccounts'])) {
            $tradeaccount->attributes = $_POST['Tradeaccounts'];
            $tradeaccount->userID = Yii::app()->user->getId();
            $country = Countries::model()->findByPk($user->country);
            if ($tradeaccount->validate()) {
                $mt = new MTconnector();
                if ($mt->connected()) {
                    $fxtype = Fxtypes::model()->findByPk($tradeaccount->fxType);
                    /// пароль к счёту
                    $length = 10;
                    $chars = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
                    shuffle($chars);
                    $password = implode(array_slice($chars, 0, $length));
                    $tradeaccount->password = md5($password);
                    /// инвесторский пароль
                    $length1 = 10;
                    $chars1 = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
                    shuffle($chars1);
                    $password1 = implode(array_slice($chars1, 0, $length1));
                    $agent_account = intval($user->agent_account);
                    if (!$agent_account && $user->partneraccount) {
                        $agent = Users::getPartnerAccountByTransitID($user->partneraccount);
                        if ($agent) {
                            $agent_account = intval($agent->mtID);
                        }
                    }
                    /// пароль к счёту $password;
                    $mtuser = [
                        'login'                  => 0,                                           // login
                        'group'                  => $fxtype->mtGroup,                   // group
                        'password'               => $password,                                // password
                        'password_investor'      => $password1,
                        'enable'                 => 1,                    // enable
                        'enable_change_password' => 1,                      // allow to change password
                        'enable_read_only'       => 0,                // allow to open/positions (TRUE-may not trade)
                        'zipcode'                => $user->transitID,
                        'password_phone'         => iconv("UTF-8", "cp1251", $user->phonePassword),                             // phone password
                        'name'                   => iconv("UTF-8", "cp1251", "{$user->familyName} {$user->givenName} {$user->middleName}"), // name
                        'country'                => $country->eng,                    // country
                        'city'                   => iconv("UTF-8", "cp1251", $user->city),                                    // city
                        'state'                  => "",                    // state
                        'address'                => iconv("UTF-8", "cp1251", $user->address),                       // address
                        'phone'                  => iconv("UTF-8", "cp1251", $user->phone),                             // phone
                        'email'                  => iconv("UTF-8", "cp1251", $user->email),                             // email
                        'comment'                => "Trade account",                       // comment
                        'leverage'               => intval($tradeaccount->leverage),                    // leverage
                        'agent_account'          => $agent_account ? $agent_account : intval($user->partneraccount)
                    ];
                    $mtid = $mt->recordNew($mtuser);
                    if ($mtid > 0) {
                        ///письмо
                        $params = [
                            'firstName'         => $user->givenName,
                            'middleName'        => $user->middleName,
                            'mtid'              => $mtid,
                            'password'          => $password,
                            'language'          => $_GET['language'],
                            'password_investor' => $password1
                        ];
                        Mail::send('new', $params, $user->email);
                        $tradeaccount->mtID = $mtid;
                        $tradeaccount->save();
                        Yii::app()->user->setFlash('notice acc', [
                            'header' => Yii::t('alert', "Спасибо за открытие торгового счета!"),
                            'text'   => Yii::t('alert', 'Пароль для входа в торговый терминал FxPrivate Meta Trader 4 отправлен Вам на электронную почту, указанную Вами при регистрации Личного кабинета.')
                        ]);
                    } /*
                     * !!!! Добавить обработчик ошибки
                     */
                } else {
                }
                $this->redirect(Yii::app()->createUrl("account/index"));
            } else {
                $er = $tradeaccount->getErrors();
            }
        }
        $this->render('newacc', ['model' => $tradeaccount, 'fxtypes' => $fxtypes]);
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', ['model' => $model]);
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionPaymenthistory() {
        $model = new Transfers('history');
        $model->unsetAttributes();
        if (isset($_GET['Transfers'])) {
            $model->attributes = $_GET['Transfers'];
        }
        $this->render('paymenthistory', ['model' => $model, 'types' => Transfers::getTypesList(), 'statuses' => Transfers::getStatusesList()]);
    }

    public function actionPayoutcancel() {
        if (isset($_POST['payment'])) {
            $id = intval($_POST['payment']);
            if ($id) {
                $model = Transfers::model()->findByAttributes([
                    'ID'     => $id,
                    'status' => '1',
                    'issuer' => Users::model()->findByPk(Yii::app()->user->getId())->ID
                ]);
                if ($model) {
                    $model->status = 5;
                    if ($model->save()) {
                        echo $model->status;
                    }
                }
            }
        }
    }

    public function actionTransfer() {
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        $transfer = new TransferForm();
        if (isset($_POST['TransferForm'])) {
            $transfer->attributes = $_POST['TransferForm'];
            if ($transfer->validate()) {
                $transfer->completeTransfer();
            }
        };
        $transits = Transitaccounts::model()->
        find(
            [
                'condition' => 'userID="' . Yii::app()->user->getId() . '" and currency="840"',
            ]
        );
        $tradeaccounts = Tradeaccounts::model()->with('fxType_')->findAll(
            ['condition' => 'userID=' . Yii::app()->user->getId()]
        );
        foreach ($tradeaccounts as $key => $val) {
            $request[] = intval($val['mtID']);
        }
        $mtdata = [];
        if (!empty ($request)) {
            $mt = new MTconnector();
            if ($mt->connected()) {
                $mt4accounts = $mt->find($request);
                foreach ($mt4accounts as $key => $val) {
                    $mtdata[$val['login']]['leverage'] = $val['leverage'];
                    $mtdata[$val['login']]['balance'] = $val['balance'];
                }
            }
        }
        $this->render('transfer', [
            'tradeaccounts' => $tradeaccounts,
            'transfer'      => $transfer,
            'mtdata'        => $mtdata,
            'transitID'     => $user->transitID,
            'transit'       => $transits['amount'],
        ]);
    }
}