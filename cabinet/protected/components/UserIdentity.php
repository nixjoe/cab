<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
//	public function authenticate()
//	{
//		$users=array(
//			// username => password
//			'demo'=>'demo',
//			'admin'=>'admin',
//		);
//		if(!isset($users[$this->username]))
//			$this->errorCode=self::ERROR_USERNAME_INVALID;
//		else if($users[$this->username]!==$this->password)
//			$this->errorCode=self::ERROR_PASSWORD_INVALID;
//		else
//			$this->errorCode=self::ERROR_NONE;
//		return !$this->errorCode;
//	}
    
   private $_id;
   public $mail;
   private $_key;
    public function authenticate()
    {
        $record=Users::model()->findByAttributes(array('email'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        //else if($record->STATUS == 0) $this->errorCode=self::ERROR_STATUS_NOTACTIV;
        else if($record->password!==base64_encode(pack('H*', sha1($this->password))))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {				
            $this->_id=$record->ID;
            $this->mail=$this->username;
            $this->setState('status', $record->status);
            
        		$key = md5(microtime().rand());
				Yii::app()->request->cookies['login_key'] = new CHttpCookie('login_key', $key);			
				$record->login_key = $key;       
				$record->save();				     
				      
            $manager = UsersManagers::model()->findByPk($record->ID);
            if (!empty($manager)) {
                $this->setState('role', $manager->role);
            } else $this->setState('role', 'user');
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }    
    
}