<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
   public $verifyCode;
   public $language;
   private $login_key;
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
        
    private function getRemoteIP ()
  {

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
        
	public function rules()
	{
		return array(
			// username and password are required
          //array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd'),'enableClientValidation'=>false,'message'=>Yii::t('messages','Неправильный код проверки.')),
			array('username, password', 'required', 'message'=>Yii::t('messages', 'Поле не должно быть пустым')),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>Yii::t('login', 'Запомнить'),
                        'verifyCode'=>Yii::t('login', 'Введите код на картинке'),
                        'password'=>Yii::t('login', 'Пароль'),
                        'username'=>'Email',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password',Yii::t('messages', 'Неправильные логин или пароль'));
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{			
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{		
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}