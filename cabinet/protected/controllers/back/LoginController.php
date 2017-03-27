<?php
error_reporting(E_ALL);
class LoginController extends CController
{

    public $language;
    public $langshort;
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
//        $cookies = Yii::app()->request->getCookies();
//        if(isset($cookies['locale']->value)) Yii::app()->setLanguage($cookies['locale']->value);
//        else{
//            $cookies['locale']=new CHttpCookie("locale","ru");
//            Yii::app()->setLanguage($cookies['locale']->value);
//        }
//        $this->language = Yii::app()->getLanguage();
//        $this->langshort = strtoupper($this->language[0]);
    }

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

        public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0x141517,
                'maxLength' => '7',
                'minLength' => '7',
                'width' => '110',
                'height' => '35',
                'foreColor' => 0xC7C7C7,
                'testLimit'=>0
            ),
        );
    }
    
    public function actionIndex()
	{
		//if (Yii::app()->user->isGuest) {
		//		$this->redirect(YII::app()->createUrl("site/login"));
        //    }

		$model=new LoginsForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		// collect user input data

		if(isset($_POST['LoginsForm']))
		{

			$model->attributes=$_POST['LoginsForm'];
			
			// validate user input and redirect to the previous page if valid

			if($model->validate() && $model->login() ){
				//Yii::app()->user->guestName = 'admin';
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		// display the login form
			
				
			$this->render('login',array('model'=>$model));
		
	}

    
   

    public function actionLogout()
    {
            $cookies = Yii::app()->request->getCookies();
            unset($cookies['selected']);
            Yii::app()->user->logout();
            $model=new LoginForm;
            $this->redirect(Yii::app()->user->returnUrl);
    }

}
?>
