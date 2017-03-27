<?php

class OfertaForm extends CFormModel
{
    public $agreed;
    public $verifyCode;
    public function rules()
    {
        return array(
            array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd'),'enableClientValidation'=>false,'message'=>Yii::t('messages','Неправильный код проверки.')),
            array('agreed', 'required', 'requiredValue'=>1, 'message'=>Yii::t('reg', 'Пожалуйста, подтвердите согласие')),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'agreed'=>Yii::t('reg', 'Ознакомился и согласен'),
            'verifyCode'=>Yii::t('account', 'Введите код на картинке'),
        );
    }
}