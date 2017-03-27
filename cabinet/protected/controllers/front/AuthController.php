<?php

//error_reporting(E_ALL);
class AuthController extends CController
{
    public $langshort;

    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        // Set the application language if provided by GET, session or cookie
        if (isset($_POST['language'])) {
            $lang = $_POST['language'];
            $MultilangReturnUrl = $_POST[$lang];
            $this->redirect($MultilangReturnUrl);
        }
        $languages = new Languages();
        $users = new Users();
        $user = $users->findByPK(Yii::app()->user->ID);
        if (isset($_GET['language'])) {
            $lang_id = $languages->find('iso = :iso', [":iso" => $_GET['language']]);
            if (!$lang_id) {
                $_GET['language'] = 'en';
                $lang_id = $languages->find('iso = :iso', [":iso" => $_GET['language']]);
            }
        }
        // Set the application language if provided by GET, session or cookie
        if (isset($_GET['language'])) {
            if (isset($_SESSION['lastlang']) && $_SESSION['lastlang'] != $_GET['language'] && isset($_SESSION['lastpost'])) {
                $_POST = $_SESSION['lastpost'];
            }
            if (isset($_POST)) {
                $_SESSION['lastpost'] = $_POST;
            } else {
                unset($_SESSION['lastpost']);
            }
            if (isset($_GET['language'])) {
                $_SESSION['lastlang'] = $_GET['language'];
            }
            if (isset($user) && isset($lang_id) && $this->id != 'payment') {
                $user->language = $lang_id->id;
                $user->save();
            }
            Yii::app()->language = isset(Yii::app()->params['urlCountry'][$_GET['language']]) ? Yii::app()->params['urlCountry'][$_GET['language']] :
                $_GET['language'];
            Yii::app()->user->setState('language', isset(Yii::app()->params['urlCountry'][$_GET['language']]) ?
                Yii::app()->params['urlCountry'][$_GET['language']] : $_GET['language']);
            $cookie = new CHttpCookie('language', isset(Yii::app()->params['urlCountry'][$_GET['language']]) ?
                Yii::app()->params['urlCountry'][$_GET['language']] : $_GET['language']);
            $cookie->expire = time() + (60 * 60 * 24 * 365); // (1 year)
            Yii::app()->request->cookies['language'] = $cookie;
        } else {
            if (isset($user) && $user->language && isset($id) && $id != 'account') {
                $userLang = $languages->findByPK($user->language);
                if (isset($userLang)) {
                    $_GET['language'] = $userLang->iso;
                } else {
                    $_GET['language'] = 'ru';
                }
                Yii::app()->language = isset(Yii::app()->params['urlCountry'][$_GET['language']]) ?
                    Yii::app()->params['urlCountry'][$_GET['language']] : $_GET['language'];
                Yii::app()->user->setState('language', isset(Yii::app()->params['urlCountry'][$_GET['language']]) ?
                    Yii::app()->params['urlCountry'][$_GET['language']] : $_GET['language']);
                $cookie = new CHttpCookie('language', isset(Yii::app()->params['urlCountry'][$_GET['language']]) ?
                    Yii::app()->params['urlCountry'][$_GET['language']] : $_GET['language']);
                $cookie->expire = time() + (60 * 60 * 24 * 365); // (1 year)
                Yii::app()->request->cookies['language'] = $cookie;
            } else {
                if (isset(Yii::app()->request->cookies['language'])) {
                    Yii::app()->language = Yii::app()->request->cookies['language']->value;
                    $_GET['language'] = Yii::app()->request->cookies['language']->value;
                    if ($_GET['language'] == 'uk') {
                        $_GET['language'] = 'ua';
                    }
                    if ($_GET['language'] == 'zh_cn') {
                        $_GET['language'] = 'cn';
                    }
                } else {
                    if (Yii::app()->user->hasState('language')) {
                        Yii::app()->language = Yii::app()->user->getState('language');
                        $_GET['language'] = Yii::app()->user->getState('language');
                        if ($_GET['language'] == 'uk') {
                            $_GET['language'] = 'ua';
                        }
                        if ($_GET['language'] == 'zh_cn') {
                            $_GET['language'] = 'cn';
                        }
                    } else {
                        $user_ip = $this->getRemoteIP();
                        if ($user_ip) {
                            $user_country = $this->getCountryByIp($user_ip, true);
                        }
                        $_GET['language'] = strtolower($user_country);
                        Yii::app()->language = strtolower($user_country);
                        Yii::app()->user->setState('language', isset(Yii::app()->params['urlCountry'][$_GET['language']]) ?
                            Yii::app()->params['urlCountry'][$_GET['language']] : $_GET['language']);
                        $cookie = new CHttpCookie('language', isset(Yii::app()->params['urlCountry'][$_GET['language']]) ?
                            Yii::app()->params['urlCountry'][$_GET['language']] : $_GET['language']);
                        $cookie->expire = time() + (60 * 60 * 24 * 365); // (1 year)
                        Yii::app()->request->cookies['language'] = $cookie;
                    }
                }
            }
        }
        /* if(isset($_GET['language'])) {

             Yii::app()->language = isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language'];
             Yii::app()->user->setState('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']);
             $cookie = new CHttpCookie('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']);
             $cookie->expire = time() + (60*60*24*365); // (1 year)
             Yii::app()->request->cookies['language'] = $cookie;
             //die(Yii::app()->language);
         }
         else if (Yii::app()->user->hasState('language')) {
             Yii::app()->language = Yii::app()->user->getState('language');
             $_GET['language'] = Yii::app()->user->getState('language');
         } else if(isset(Yii::app()->request->cookies['language'])) {
             Yii::app()->language = Yii::app()->request->cookies['language']->value;
             $_GET['language'] = Yii::app()->request->cookies['language']->value;
         }else{

            $user_ip = $this->getRemoteIP();
              if($user_ip) $user_country = $this->getCountryByIp($user_ip, true);
                 $_GET['language'] = strtolower($user_country);
                 Yii::app()->language = strtolower($user_country);
             Yii::app()->user->setState('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']);
             $cookie = new CHttpCookie('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']);
             $cookie->expire = time() + (60*60*24*365); // (1 year)
             Yii::app()->request->cookies['language'] = $cookie;
         }
         if($_GET['language'] == 'uk') $_GET['language'] = 'ua';
         if($_GET['language'] == 'zh_cn') $_GET['language'] = 'cn';*/
    }

    private function getRemoteIP() {
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && strlen($_SERVER["HTTP_X_FORWARDED_FOR"]) > 0) {
            $f = $_SERVER["HTTP_X_FORWARDED_FOR"];
            $reserved = false;
            if (substr($f, 0, 3) == "10.") {
                $reserved = true;
            }
            if (substr($f, 0, 4) == "172." && substr($f, 4, 2) > 15 && substr($f, 4, 2) < 32) {
                $reserved = true;
            }
            if (substr($f, 0, 8) == "192.168.") {
                $reserved = true;
            }
            if (!$reserved) {
                $ip = $f;
            }
        }
        if (!isset($ip)) {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        return $ip;
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
        ];
    }

    public function actionIndex() {
        $this->layout = 'signup';
        $model = new LoginForm;
        //if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                $cookies = Yii::app()->request->getCookies();
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
                if (empty($user->paymentPassword)) {
                    $this->redirect(YII::app()->createUrl("/login/paymentPassword"));
                } else {
                    $this->redirect(Yii::app()->user->returnUrl);
                }
            } else {
                Yii::app()->user->setFlash('error', [
                    'header' => Yii::t('alert', "Необходимо исправить следующие ошибки:"),
                    'text'   => CHtml::errorSummary($model, '', '')
                ]);
            }
        }
        if (Yii::app()->user->isGuest) {
            $this->render('login', ['model' => $model]);
        } else {
            $this->redirect(Yii::app()->homeUrl);
        }
    }

    private function getCountryByIp2($ipAddress) {
        $ipDetail = [];
        $f = json_decode(file_get_contents("http://ru.smart-ip.net/geoip-json/" . $ipAddress . "/auto/"));
        $country = $f->countryCode;
        $isoid = Countries::model()->find(
            [
                'condition' => 'iso2=:iso2',
                'params'    => [':iso2' => $country]
            ]
        );

        return $isoid->isoID;
    }

    private function getCountryByIp($ipAddress, $returnLang = false) {
        $ipDetail = [];
        //$f = json_decode(@file_get_contents("http://ru.smart-ip.net/geoip-json/".$ipAddress."/auto/"));
        //if (isset($f) && isset($f->countryCode) && $f->countryCode){
        //	$country = $f->countryCode;
        //}else{
        //	$country = "ua";
        //}
        $details = json_decode(@file_get_contents("http://ipinfo.io/{$ipAddress}"));
        $country = strtolower(isset($details->country) ? $details->country : ($returnLang ? 'ru' : 'ua'));
        $isoid = Countries::model()->find(
            [
                'condition' => 'iso2=:iso2',
                'params'    => [':iso2' => $country]
            ]
        );
        if (!$returnLang) {
            return $isoid ? $isoid->isoID : '';
        }
        if (in_array(strtolower($country), ['ab', 'az', 'am', 'by', 'ge', 'kz', 'kg', 'lv', 'lt', 'md', 'ru', 'tj', 'tm', 'uz', 'ee'])) {
            $lang_url = "ru";
        } elseif (strtolower($country) == 'ua') {
            $lang_url = "ua";
        } elseif (strtolower($country) == 'id') {
            $lang_url = "id";
        } elseif (strtolower($country) == 'cn') {
            $lang_url = "cn";
        } elseif (strtolower($country) == 'es') {
            $lang_url = "es";
        } elseif (strtolower($country) == 'my') {
            $lang_url = "my";
        } elseif (strtolower($country) == 'il') {
            $lang_url = "ar";
        } else {
            $lang_url = "en";
        }

        return $lang_url;
    }

    private function get_domain($url) {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }

        return false;
    }

    public function actionRegister() {
        $this->layout = 'signup';
        $route = (Yii::app()->getRequest());
        if (preg_match('/\/(\d+)[\/\?#&]?$/', $route->pathInfo, $_matches)) {
            Yii::app()->session['partnerurl'] = intval(@$_matches[1]);
        } elseif (!Yii::app()->request->isPostRequest && !Yii::app()->request->isAjaxRequest) {
            $_partnerURL = false;
            $referer = isset($_SERVER['HTTP_REFERER']) ? $this->get_domain($_SERVER['HTTP_REFERER']) : '';
            $this_domain = @$_SERVER['HTTP_HOST'];
            if ($referer && stripos($this_domain, $referer) !== false) {
                $_partnerURL = isset($_COOKIE['_ref_id']) ? $_COOKIE['_ref_id'] : false;
            }
            if (!$_partnerURL) {
                $_partnerURL = Yii::app()->session['partnerurl'];
            }
            if (!$_partnerURL) {
                $_partnerURL = isset($_COOKIE['_ref_id']) ? $_COOKIE['_ref_id'] : false;
            }
            if ($_partnerURL && preg_match('/^\d+$/', $_partnerURL)) {
                $this->redirect(Yii::app()->createUrl('/auth/register/' . $_partnerURL));
            }
        }
        if (!isset($_POST['OfertaForm']) && !isset($_POST['Users'])) {
            $oferta = new OfertaForm();
            $this->render('oferta', ['oferta' => $oferta]);

            return (true);
        } elseif (isset($_POST['OfertaForm'])) {
            $oferta = new OfertaForm();
            $oferta->attributes = $_POST['OfertaForm'];
            if (!$oferta->validate()) {
                $this->render('oferta', ['oferta' => $oferta]);
            } else {
                Yii::app()->session['oferta'] = true;
            }
        }
        if (!empty(Yii::app()->session['oferta']) && Yii::app()->session['oferta']) {
            $model = new Users('register');
            if (isset($_POST['Users'])) {
                $model->attributes = $_POST['Users'];
                $model->regdate = time();
                $model->status = 0;
                $model->group = 0;
                $country = Countries::model()->findByPk($model->country);
                $lang = Languages::model()->findByAttributes(['active' => 1, 'iso' => '' . $_GET['language'] . ''], ['order' => 'iso']);
                if ($model->validate()) {
                    error_log(" time " . time() . " register user " . $model->email . " \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
                    if (isset($lang) && isset($lang->iso) && $lang->iso) {
                        $langname = $lang->iso;
                    } else {
                        $langname = "en";
                    }
                    $params = [
                        'firstName'  => $model->givenName,
                        'middleName' => $model->middleName,
                        'login'      => $model->email,
                        'password'   => $_POST['Users']['password'],
                        'language'   => $langname
                    ];
                    error_log(" time " . time() . " " . $langname . " language OK \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
                    error_log(" time " . time() . " " . $this->getRemoteIP() . " IP OK \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
                    Mail::send('signup', $params, $model->email);
                    $mt = new MTconnector();
                    if ($mt->connected()) {
                        $mtuser = [
                            'login'                  => 0,                                           // login
                            'group'                  => "transitacc",                                // group
                            'enable'                 => 1,                        // enable
                            'enable_change_password' => 0,                          // allow to change password
                            'enable_read_only'       => 1,                // allow to open/positions (TRUE-may not trade)
                            'password_investor'      => $model->password,                // read-only mode password
                            'password_phone'         => iconv("UTF-8", "cp1251", $model->phonePassword), // phone password
                            'name'                   => iconv("UTF-8", "cp1251", "{$model->familyName} {$model->givenName} {$model->middleName}"),
                            // name
                            'country'                => $country->eng,                // country
                            'city'                   => iconv("UTF-8", "cp1251", $model->city),       // city
                            'state'                  => "",                                          // state
                            'phone'                  => iconv("UTF-8", "cp1251", $model->dialcode . $model->phone),     // phone
                            'email'                  => iconv("UTF-8", "cp1251", $model->email),     // email
                            'comment'                => "Transit account",                         // comment
                            'leverage'               => 1,                    // leverage
                        ];
                        if (!empty(Yii::app()->session['partnerurl']) && Yii::app()->session['partnerurl']) {
                            // check referer partner
                            $ref_id = intval(Yii::app()->session['partnerurl']);
                            $refUser = Users::getUserByPartnerAcc($ref_id);
                            $refPartner = true;
                            //for old links
                            if (!$refUser) {
                                $refPartner = false;
                                $refUser = Users::model()->find(['condition' => "partner='2' AND transitID='$ref_id'"]);
                            }
                            $mtuser['agent_account'] = $refUser ? ($refPartner ? $ref_id : intval($refUser->transitID)) : 0;
                            if ($refUser) {
                                if ($refPartner) {
                                    $model->agent_account = $ref_id;
                                } else {
                                    $model->partneraccount = $refUser->transitID;
                                }
                            }
                            Yii::app()->session['oferta'] = false;
                        }
                        $mtid = $mt->recordNew($mtuser);
                        if ($mtid > 0) {
                            $model->transitID = $mtid;
                        }
                        $model->save();
                        // Открываем аккаунт в долларах
                        $trans = new Transitaccounts();
                        $trans->currency = 840;
                        $trans->userID = $model->primaryKey;
                        $trans->opendate = new CDbExpression('NOW()');
                        $trans->save();
                        // Открываем аккаунт в евро
                        $trans = new Transitaccounts();
                        $trans->currency = 978;
                        $trans->userID = $model->primaryKey;
                        $trans->opendate = new CDbExpression('NOW()');
                        $trans->save();
                        // Проверяем, что валюта, в которой мы должны открыть счет активнаи не равна USD / EUR
                        $cur = $country->currency_();
                        if (!$cur->disabled && $country->curID <> 840 && $country->curID <> 978) {
                            $trans = new Transitaccounts();
                            $trans->currency = $country->curID;
                            $trans->userID = $model->primaryKey;
                            $trans->opendate = new CDbExpression('NOW()');
                            $trans->save();
                        }
                        Yii::app()->user->setFlash('success', [
                            'header' => Yii::t('reg', "Регистрация прошла успешно."),
                            'text'   => Yii::t('reg', 'Пожалуйста, войдите в личный кабинет, <br/> используя Ваш логин и пароль.')
                        ]);
                        /*$params = array (
                            'firstName'=>$model->givenName,
                            'middleName'=>$model->middleName,
                            'login'=>$model->email,
                            'password'=>$_POST['Users']['password'],
                            'language'=>$_GET['language']
                        );

                        Mail::send('signup', $params, $model->email);*/
                        $this->redirect(Yii::app()->createUrl("/login/"));
                    } else {
                        Yii::app()->user->setFlash('error', [
                            'header' => Yii::t('reg', "Ошибка связи с сервером"),
                            'text'   => Yii::t('reg', 'Пожалуйста, попробуйте еще раз через некоторое время.')
                        ]);
                    }
                }
            }
            if ($_GET['language'] == 'ru') {
                $lng = 'rus';
            } else {
                $lng = 'eng';
            }
            $selectfields = [
                'isoID'       => 'isoID',
                'dialcode'    => 'dialcode',
                'country'     => $lng,
                'country_alt' => $lng . '_alt',
                'iso2'        => 'iso2',
                'iso3'        => 'iso3',
                'mul'         => 'mul'
            ];
            $countrylist = Countries::model()->findAll([
                'select'    => $selectfields,
                'condition' => 'disabled=0',
                'order'     => $lng . ' ASC'
            ]);
            $user_ip = $this->getRemoteIP();
            if ($user_ip) {
                $user_country = $this->getCountryByIp($user_ip);
            }
            $this->render('register', ['model' => $model, 'countrylist' => $countrylist, 'user_ip' => $user_ip, 'user_country' => $user_country]);
        }
    }

    public function actionPaymentPassword() {
        if (Yii::app()->user->isGuest) {
            $this->redirect(YII::app()->createUrl("login"));
        } else {
            $u = Users::model()->findByPk(Yii::app()->user->getId());
            //if (!empty($u->paymentPassword)) $this->redirect(Yii::app()->homeUrl);
        }
        $this->layout = 'signup';
        $paypassform = new PayPassForm();
        if (isset($_POST['PayPassForm'])) {
            $paypassform->attributes = $_POST['PayPassForm'];
            $paypassform->store();
            if (!$paypassform->hasErrors()) {
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        Yii::app()->user->setFlash('notice', [
            'header' => Yii::t('alert', "Платежный пароль"),
            'text'   => Yii::t('paypass', 'Перед началом работы необходимо создать пароль, который будет использоваться при проведении финансовых операций, требующих повышенной защиты. Пожалуйста, запомните или запишите Ваш платежный пароль. В целях безопасности он не высылается по электронной почте.')
        ]);
        $this->render('paypassform', ['paypassform' => $paypassform]);
    }

    public function actionLogout() {
        $cookies = Yii::app()->request->getCookies();
        unset($cookies['selected']);
        Yii::app()->user->logout();
        $model = new LoginForm;
        $this->redirect(Yii::app()->user->returnUrl);
    }

    public function actionRestore() {
        if (!Yii::app()->user->isGuest) {
            return $this->redirect(Yii::app()->createUrl("login"));
        }
        $this->layout = 'signup';
        $model = new ResetForm();
        if (isset($_POST['ResetForm'])) {
            $model->attributes = $_POST['ResetForm'];
            if ($model->validate()) {
                $user = Users::model()->findbyPk($model->userID);
                $user->verifyCode = md5(microtime() . '_' . $user->email);
                $user->save();
                $langIso = isset($_GET['language']) && !empty($_GET['language']) ? $_GET['language'] : 'en';
                $lang = Languages::model()->findByAttributes(['active' => 1, 'iso' => $langIso], ['order' => 'iso']);
                $params = [
                    'firstName'  => $user->givenName,
                    'middleName' => $user->middleName,
                    'login'      => $user->email,
                    'language'   => $lang ? $lang->iso : 'en',
                    'resetURL'   => $this->createAbsoluteUrl('auth/reset', ['email' => $user->email, 'verify' => $user->verifyCode]),
                ];
                if (Mail::send('restore', $params, $user->email)) {
                    Yii::app()->user->setFlash('notice', [
                        'header' => Yii::t('auth', "Инструкция по восстановлению пароля отправлена на указаный E-mail"),
                        'text'   => ''
                    ]);
                    $this->refresh();
                }
            } else {
                $error = $model->getError('username');
                if ($error == 'NOT_EXISTS') {
                    $error = Yii::t('auth', 'Такой адрес не зарегистрирован') . '<br /><br /><a class="link-default" href="'
                        . $this->createUrl('auth/register') . '">' . Yii::t('auth', 'Зарегистрироваться') . '</a>';
                    $model->clearErrors('username');
                    $model->addError('username', Yii::t('auth', 'Такой адрес не зарегистрирован'));
                }
                Yii::app()->user->setFlash('error', [
                    'header' => $error,//Yii::t('alert', "Необходимо исправить следующие ошибки:"),
                    'text'   => ''
                ]);
            }
        } elseif (isset($_REQUEST['email'])) {
            $model->username = $_REQUEST['email'];
        }
        $this->render('restore', ['model' => $model]);
    }

    public function actionReset() {
        if (!Yii::app()->user->isGuest || !isset($_REQUEST['email'], $_REQUEST['verify']) || empty($_REQUEST['verify'])
            || empty($_REQUEST['email'])
        ) {
            return $this->redirect(Yii::app()->createUrl("login"));
        }
        $email = $_REQUEST['email'];
        $verifyCode = $_REQUEST['verify'];
        $user = Users::model()->findByAttributes([
            'email'      => $email,
            'verifyCode' => $verifyCode,
        ]);
        if ($user) {
            $user->setScenario('reset');
            $newPassword = $this->generateRandomPassword();
            $user->password = $newPassword;
            if ($user->save(false, ['password', 'verifyCode'])) {
                $langIso = isset($_GET['language']) && !empty($_GET['language']) ? $_GET['language'] : 'en';
                $lang = Languages::model()->findByAttributes(['active' => 1, 'iso' => $langIso], ['order' => 'iso']);
                $params = [
                    'firstName'  => $user->givenName,
                    'middleName' => $user->middleName,
                    'login'      => $user->email,
                    'password'   => $newPassword,
                    'language'   => $lang ? $lang->iso : 'en',
                    'loginURL'   => $this->createAbsoluteUrl('auth'),
                ];
                if (Mail::send('reset', $params, $user->email)) {
                    Yii::app()->user->setFlash('notice', [
                        'header' => Yii::t('auth', "Ваш новый пароль отправлен на указаный E-mail"),
                        'text'   => ''
                    ]);
                } else {
                    $user->setScenario('update');
                    $user->verifyCode = $verifyCode;
                    $user->save();
                }
            }
        }

        return $this->redirect(Yii::app()->createUrl('restore'));
    }

    protected function generateRandomPassword() {
        $letters = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nums = '1234567890';
        $arr = [
            substr(str_shuffle($nums), 0, 1),
            substr(str_shuffle($letters), 0, 1),
            substr(str_shuffle($letters . $nums), 0, 6)
        ];
        shuffle($arr);

        return implode($arr);
    }
}