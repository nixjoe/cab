<?php

class PayoutForm extends CFormModel
{
    public $method;
    public $amount;
    public $source;
    public $transit;
    
    
    public function rules()
    {
        return array(
        );
    }

    public function attributeLabels()
    {
        return array(
            'method'=>Yii::t('payout', 'Способ вывода'),
            'amount'=>Yii::t('payout', 'Сумма'),
            'source'=>Yii::t('payout', 'Счет'),
        );
    }
}

?>
