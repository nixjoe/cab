<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
        
        public $user;

        public $_locale;
        
        public function __construct($id, $module = null) {
            parent::__construct($id, $module);

			//die($_GET['language']);

		    // If there is a post-request, redirect the application to the provided url of the selected language 
		    if(isset($_POST['language'])) {
		        $lang = $_POST['language'];
		        $MultilangReturnUrl = $_POST[$lang];
		        $this->redirect($MultilangReturnUrl);
		    }
		    	 $languages = new Languages();
		    	 $users = new Users();
		    	 $user = $users->findByPK(Yii::app()->user->ID);

			if(isset($_GET['language'])) {
				$lang_id = $languages->find('iso = :iso', array(":iso" => $_GET['language']));
				if(!$lang_id) {
					$_GET['language'] = 'en';
					$lang_id = $languages->find('iso = :iso', array(":iso" => $_GET['language']));	
				}
			}

		    // Set the application language if provided by GET, session or cookie
		    if(isset($_GET['language'])) {
		    	
		    		if(isset($_SESSION['lastlang']) && $_SESSION['lastlang'] != $_GET['language'] && isset($_SESSION['lastpost'])) {
		    			$_POST = $_SESSION['lastpost'];
		    		}
		    		
		    		if(isset($_POST)) $_SESSION['lastpost'] = $_POST;
		    		else unset($_SESSION['lastpost']);
		    	 	if(isset($_GET['language'])) $_SESSION['lastlang'] = $_GET['language'];
					if(isset($user) && isset($lang_id) && $this->id != 'payment') {
		    	 			$user->language = $lang_id->id;
		    	 			$user->save();
						}
				
		        Yii::app()->language = isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language'];
		        Yii::app()->user->setState('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']); 
		        $cookie = new CHttpCookie('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']);
		        $cookie->expire = time() + (60*60*24*365); // (1 year)
		        Yii::app()->request->cookies['language'] = $cookie; 
		    }else if(isset($user) && $user->language && isset($id) && $id != 'account'){
		    	$userLang = $languages->findByPK($user->language);
				if(isset($userLang)) {
				  $_GET['language'] = $userLang->iso;
				} else {
					 $_GET['language'] = 'ru';
					}
		        Yii::app()->language = isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language'];
		        Yii::app()->user->setState('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']); 
		        $cookie = new CHttpCookie('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']);
		        $cookie->expire = time() + (60*60*24*365); // (1 year)
		        Yii::app()->request->cookies['language'] = $cookie;
		         
		    }else if(isset(Yii::app()->request->cookies['language'])) {
		        Yii::app()->language = Yii::app()->request->cookies['language']->value;
		        $_GET['language'] = Yii::app()->request->cookies['language']->value;
		        if($_GET['language'] == 'uk') $_GET['language'] = 'ua';
		        if($_GET['language'] == 'zh_cn') $_GET['language'] = 'cn'; 		        
		    } else if (Yii::app()->user->hasState('language')) {
		        Yii::app()->language = Yii::app()->user->getState('language');
		        $_GET['language'] = Yii::app()->user->getState('language');
		        if($_GET['language'] == 'uk') $_GET['language'] = 'ua';
		        if($_GET['language'] == 'zh_cn') $_GET['language'] = 'cn';      	        
		    } else{
		        $user_ip = $this->getRemoteIP();
          	   if($user_ip) $user_country = $this->getCountryByIp($user_ip);
				$_GET['language'] = strtolower($user_country);	
				Yii::app()->language = strtolower($user_country);
		        Yii::app()->user->setState('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']); 
		        $cookie = new CHttpCookie('language', isset(Yii::app()->params['urlCountry'][$_GET['language']])?Yii::app()->params['urlCountry'][$_GET['language']]:$_GET['language']);
		        $cookie->expire = time() + (60*60*24*365); // (1 year)
		        Yii::app()->request->cookies['language'] = $cookie; 
		    }


			if(isset($id) && $id != 'payment'){
				
				if (Yii::app()->user->isGuest) {
					$this->redirect(YII::app()->createUrl("login"));
				} else {
					$this->user = Users::model()->findByPk(Yii::app()->user->getId());
					
					if(Yii::app()->request->url != YII::app()->createUrl("account/logout") && 'logout' && $this->user->login_key != Yii::app()->request->cookies['login_key']->value && $this->user->login_key != '') {
						$this->redirect(YII::app()->createUrl("account/logout"));
					}		    	
				}
			}
            /*
            $cookies = Yii::app()->request->getCookies();
            if(isset($cookies['locale']->value)) Yii::app()->setLanguage($cookies['locale']->value);
            else{
                $cookies['locale']=new CHttpCookie("locale","ru");
                Yii::app()->setLanguage($cookies['locale']->value);
            }
            $this->_locale =  Yii::app()->getLanguage();
            */
            
            if (!empty($this->user) && empty($this->user->paymentPassword)) {
                $this->redirect(YII::app()->createUrl("/auth/paymentPassword"));
            }     
				
            if(!isset($_GET['language']) && !isset($_GET['ajax']) && !Yii::app()->params['admin_side']) {
					
            	//if(isset($_GET['dima'])) die($_GET['language']);
            	$this->redirect(YII::app()->createUrl("/account/index"));
            }             
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
   
    private function getCountryByIp($ipAddress)
	  {
	    $ipDetail=array();
	    
	   /* $f = json_decode(@file_get_contents("http://ru.smart-ip.net/geoip-json/".$ipAddress."/auto/")); 
		if (isset($f) && isset($f->countryCode) && $f->countryCode){
			$country = $f->countryCode;
		}else{
			$country = "ua";
		}*/
		
		$details = json_decode(@file_get_contents("http://ipinfo.io/{$ipAddress}"));
		$country = isset($details->country) ? strtolower($details->country) : 'ru';
		
	  	
	
		 $isoid = Countries::model()->find(
		           array (
	               'condition'=>'iso2=:iso2',
	               'params'=>array(':iso2'=>$country))
		 ); 
 		 
		 if(in_array(strtolower($country), array('ab', 'az', 'am', 'by', 'ge', 'kz', 'kg', 'lv', 'lt', 'md', 'ru', 'tj', 'tm', 'uz', 'ee'))) {
				$lang_url = "ru";
		 } elseif(strtolower($country) == 'ua') {
		 		$lang_url = "ua";
		 } elseif(strtolower($country) == 'id') {
		 		$lang_url = "id";
		 } elseif(strtolower($country) == 'cn') {
		 		$lang_url = "cn";
		 } elseif(strtolower($country) == 'es') {
		 		$lang_url = "es";
		 } elseif(strtolower($country) == 'my') {
		 		$lang_url = "my";
		 } elseif(strtolower($country) == 'il') {
		 		$lang_url = "ar";
		 } else {
		 		$lang_url = "en";
		 }
	                
	    return $lang_url;
	  }            
                
	public function createMultilanguageReturnUrl($lang='ru'){
	    if (count($_GET)>0){
	        $arr = $_GET;
	        $arr['language']= $lang;
	    }
	    else 
	        $arr = array('language'=>$lang);
	    return $this->createUrl('', $arr);
	}      
}