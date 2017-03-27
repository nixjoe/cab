<?php


class ProfileController extends Controller {

    public function actionIndex() {
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        $country = $user->country_();
        switch ($user->status) {
            case 0:
                $class='unknown';
                $title = Yii::t('alert', 'Счет не прошел аттестацию');
                Yii::app()->user->setFlash('notice',
                    array(
                        'header'=>Yii::t('alert', "Счет не прошел аттестацию"),
                        'text'=>Yii::t('alert', 'Для полноценного использования личного кабинета
                            необходимо выполнить аттестацию аккаунта. Для начала
                            аттестации нажмите на кнопку "Пройти аттестацию".')
                        )
                    );
                break;
            case 1:
                $class='valid';
                $title = Yii::t('alert', 'Счет прошел аттестацию');
                break;
            case 2:
                $class='pending';
                $title = Yii::t('alert', 'Аттестация ожидает проверки');
                Yii::app()->user->setFlash('notice',
                    array(
                        'header'=>Yii::t('alert', "Счет не прошел аттестацию"),
                        'text'=>Yii::t('alert', 'Для полноценного использования личного кабинета
                            необходимо выполнить аттестацию аккаунта. Для начала
                            аттестации нажмите на кнопку "Пройти аттестацию".')
                        )
                    );                
                break;
            case 3:
                $class='invalid';
                $title = Yii::t('alert', 'Аттестация неудачна');
                Yii::app()->user->setFlash('error',
                    array(
                        'header'=>Yii::t('alert', "При аттестации возникла ошибка"),
                        'text'=>Yii::t('alert', 'Пожалуйста, повторите аттестцию, нажав на кнопку "Пройти аттестацию".')
                        )
                    );
                break;
            default:
                $class='unknown';
                $title = Yii::t('alert', 'Счет не прошел аттестацию');
        }

        $partnerAccType = Fxtypes::model()->find(array('condition'=>'mtGroup = \'Partner\''));

        $this->render('profile',
            array(
                'user'=>$user,
                'country'=>$country->rus,
                'class'=>$class,
                'title'=>$title,
                'partnerAccType'=>$partnerAccType,
                )
            );
    }

	public function actionTransitBank() {
  		if(isset($_POST["payment_id"])) {
			$user = Users::model()->findByPk(Yii::app()->user->getId());
			$cur = $_POST['currency'];
			switch($cur) {
				case "UAH":
	        			$this->renderPartial('kvitancia', array(
							'payment_id' 			=> $_POST["payment_id"],
							'username'			=> ($user->familyName . " " . $user->givenName . " " . $user->middleName),
							'regdate'			=> ($user->regdate),
							'amount' 			=> $_POST["amount"],
						));
						exit;
					break;
				default:
					if($cur == "USD" || $cur == "EUR") {
							$this->render('retkvitancia', array(
								'payment_id' 			=> $_POST["payment_id"],
							));
						}
			}

  		}
	}

    public function actionVerification(){
        if ($this->user->status == 1 || $this->user->status == 2)
            $this->redirect(array('/profile'));
        $model=new UsersDocs;
        // uncomment the following code to enable ajax-based validation
        /*
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-docs-verification-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        */

        if(isset($_POST['UsersDocs']))
        {
            $model->attributes=$_POST['UsersDocs'];
            $model->userID = $this->user->ID;
				$model->scan = array();
            $model->scan[] = CUploadedFile::getInstance($model, 'scan');
				$model->scan[] = CUploadedFile::getInstance($model, 'scan1');
			
			
				if($model->save())
				{
					$this->user->status = 2;

					$this->user->save();
					Yii::app()->user->setFlash('success', array(
						'header'=>Yii::t('alert', "Заявка на аттестацию счета отправлена"),
						'text'=>Yii::t('alert', 'Вы будете уведомлены после рассмотрения заявки администатором.')));
						
					$subject = "Заявка на аттестацию счета";
					$text = 'Имя пользователя: ' . $this->user->familyName . " " .  $this->user->givenName . " " . $this->user->middleName . "<br>";
					$text .= 'ID пользователя - '.$this->user->ID;
					$text .= 'Email - '.$this->user->email;
					$to = "support@fx-private.com";
					$headers = 'From: support@fx-private.com' . "\r\n" .
								'Reply-To: support@fx-private.com' . "\r\n" .
								'Content-type: text/html; charset="utf-8"'. "\r\n" .
								'X-Mailer: PHP/' . phpversion();					
					mail($to, $subject, $text, $headers);

                    $langIso = isset($_GET['language']) && !empty($_GET['language']) ? $_GET['language'] : 'en';
                    $lang =  Languages::model()->findByAttributes(array('active' => 1, 'iso' => $langIso),array('order'=>'iso'));

                    $mail_params = array(
                        'firstName' => $this->user->givenName,
                        'middleName' => $this->user->middleName,
                        'lastName' => $this->user->familyName,
                        'language' => $lang ? $lang->iso : 'en',
                    );
                    Mail::send('verify_request', $mail_params, $this->user->email);
					
					$this->redirect(array('/profile'));
	// form inputs are valid, do something here
				} else Yii::app()->user->setFlash('error', array(
					'header'=>Yii::t('alert', "Необходимо исправить следующие ошибки:"),
					'text'=>CHtml::errorSummary($model, '','')));
        }
        $this->render('verification',array('model'=>$model));
    }
	
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
                            'class' => 'CCaptchaAction',
                            'backColor' => 0x141517,
                            'maxLength' => '7',
                            'minLength' => '7',
                            'width' => '110',
                            'height' => '35',
                            'foreColor' => 0xC7C7C7,
                            'testLimit'=>1
			),
			// page action renders "static" pages stored under 'protected/views/account/pages'
			// They can be accessed via: index.php?r=account/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
                                'layout'=>'faq'
			),
		);
	}
	
	 public function actionPartner(){
        if (@$_POST['accept'] && isset($_POST['leverage']) && $this->user->status == 1 && $this->user->partner == 0){
            $fxType = Fxtypes::model()->find(array('condition'=>'mtGroup = \'Partner\''));
            $leverage = intval($_POST['leverage']);

            if ($this->user->partner == 0 && $fxType && $leverage){
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
                            'group' => $fxType->mtGroup, // group
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

                        $user->partner = 2;
                        $user->save();

                        $tradeAccount = Tradeaccounts::model()->findByAttributes(array('userID'=>$user->ID, 'fxType'=>$fxType->ID));
                        if (!$tradeAccount) {
                            $tradeAccount = new Tradeaccounts();
                        }
                        $tradeAccount->userID = $user->ID;
                        $tradeAccount->leverage = $leverage;
                        $tradeAccount->fxType = $fxType->ID;
                        $tradeAccount->password = md5($password);

                        //$user->partner_mtID = $tradeAccount->mtID = $mtid = $mt->recordNew($mtuser);
                        $tradeAccount->mtID = $mtid = $mt->recordNew($mtuser);

                        if (!$mtid) {
                            throw new Exception('Partner Account not found');
                        }

                        $tradeAccount->save();
                        $result = $user->save();

                        $db->commit();
                    } catch (Exception $e) {
                        $db->rollback();
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
                }

                if (!$result) {
                    Yii::app()->user->setFlash('error', array(
                        'header'=>Yii::t('alert', "Ошибка связи с сервером"),
                        'text'=>Yii::t('alert', "Пожалуйста, попробуйте еще раз через некоторое время.")));
                }
            }

            $this->render('partner');

        } else{
            $this->redirect(array('/profile'));
        }
			
	 }

    public function actionSms() {
	    error_reporting(E_ALL);
        $sms = new SMS();
	    $sms->sendCode();
    }

}
