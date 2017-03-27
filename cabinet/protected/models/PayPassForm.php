<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PayPassForm extends CFormModel
{
	public $paymentPassword;
	public $paymentPassConfirm;
        public $verifyCode;

	public function rules()
	{
		return array(
			// username and password are required
			array('paymentPassword, paymentPassConfirm', 'required', 'message'=>Yii::t('paypass', 'Поле не должно быть пустым')),
                        array('paymentPassword', 'length', 'min'=>8, 'tooShort'=>Yii::t('paypass', 'Минимум 8 символов')),
                        array('paymentPassConfirm', 'compare', 'compareAttribute'=>'paymentPassword', 'message'=>Yii::t('paypass', 'Пароли не совпадают')),
		);
	}

	public function attributeLabels()
	{
		return array(
			'paymentPassword'=>Yii::t('paypass', 'Пароль'),
                        'paymentPassConfirm'=>Yii::t('paypass', 'Подтверждение пароля'),
		);
	}

	public function store()
	{
		if(!$this->hasErrors())
		{
                    $u = Users::model()->findByPk(Yii::app()->user->getId());
                    $u->paymentPassword = $u->hashPassword($this->paymentPassword);
                    $u->save();
                    //$this->errors .= $u->errors;
		}
	}
}