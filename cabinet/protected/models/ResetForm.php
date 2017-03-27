<?php

class ResetForm extends CFormModel
{
	public $username;
    public $userID;
    public $verifyCode;
        
	public function rules() {
		return array(
			array('username', 'required', 'message'=>Yii::t('messages', 'Поле не должно быть пустым')),
            array('username', 'email'),
            array('username', 'checkexists'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
            'username'=>'Email',
		);
	}

    public function checkexists($attribute,$params) {
        if(!$this->hasErrors()) {
            $user = Users::model()->findByAttributes(array('email'=>$this->username));
            if ($user) {
                $this->userID = $user->ID;
            } else {
                $this->addError("username", Yii::t('auth','NOT_EXISTS'));
            }
        }
    }
}