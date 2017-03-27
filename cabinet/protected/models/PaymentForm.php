<?php

class PaymentForm extends CFormModel
{
    // fields for interkassa
    public $ik_shop_id;
    public $ik_payment_amount;
    public $ik_payment_id;
    public $ik_payment_desc;
    public $ik_paysystem_alias;
    // internal fields required for validation
    public $target;


    public function rules()
    {
        return array(
            array('ik_payment_amount, target', 'required', 'on'=>'primarysubmit'),
            array('ik_payment_amount, ik_payment_id, ik_paysystem_alias, ik_payment_desc', 'required', 'on'=>'intersubmit'),
            array('ik_payment_amount', 'numerical', 'min'=>0),
            array('target','checkConditions'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'target'=>Yii::t('payment', 'Выберите счет для пополнения'),
            'ik_paysystem_alias'=>Yii::t('payment', 'Платежная система'),
            'ik_payment_amount'=>Yii::t('payment', 'Сумма'),
        );
    }

    public function checkConditions ($attribute,$params) {
        if(!$this->hasErrors()){
            $a = explode('-', $this->target);
            if (count($a)>1) {
                $t = Transitaccounts::model()->findByAttributes(array('userID'=>Yii::app()->user->getId(),'currency'=>$a[1]));
            }
            else
            {
                $t = Tradeaccounts::model()->findByAttributes(array("mtID"=>$this->target,"userID"=>Yii::app()->user->getId()));
            }
            if (empty($t)) {
                $this->addError('target', 'Ошибка входящих данных');
            }
        }
    }
}