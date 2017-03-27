<?php

class ConvertForm extends CFormModel
{
    public $from;
    public $to;
    public $curpair;
    public $give;
    public $get;

    public function rules()
    {
        return array(
            array('from, to, give', 'required'),
            array('give', 'numerical', 'min'=>0),
        );
    }

    public function attributeLabels()
    {
        return array(
        );
    }

    public function convert()
    {
        $mt = new MTconnector();
        if ($mt->connected()) {
            $rates = $mt->rates(array($this->from, $this->to), false);
            if (!empty($rates["{$this->from}{$this->to}"])){
                $rate=(float)$rates["{$this->from}{$this->to}"];
            } else {
                $this->addError('curpair', 'Данная валютная пара не задана на сервере');
            }
        }
        if(!$this->hasErrors()){
            $f = Transitaccounts::model()->
                with(
                    array('currency_',)
                )->
                    find(
                    array('condition'=>'userID='.Yii::app()->user->getId().' AND alphaCode="' . $this->from .'"')
                );
            if ($this->give > $f->amount) {
                $this->addError('give','Сумма превышает доступную на счету');
            } else {

                $f->amount = number_format(($f->amount - $this->give), 6, '.', '');
                $f->save();

                $t = Transitaccounts::model()->
                    with(
                        array('currency_',)
                    )->
                        find(
                        array('condition'=>'userID='.Yii::app()->user->getId().' AND alphaCode="' . $this->to .'"')
                    );
                //$t->amount = $t->amount + floor($this->give * $rate * 10000)/10000;
                $t->amount = $t->amount + round($this->give * $rate, 2);
                $t->save();
                $from = Currencies::model()->findByAttributes(array('alphaCode'=>$this->from));
                $to = Currencies::model()->findByAttributes(array('alphaCode'=>$this->to));
                $log = new Transfers();
                $log->issuer = Yii::app()->user->getId();
                $log->date = new CDbExpression('now()');
                $log->amount = $this->give;
                $log->actualamount = $this->give;
                $log->comission = 0;
                $log->currency = $from->curID;
                $log->currencyN= $to->curID;
                $log->sourceID = 0;
                $log->targetID = 0;
                $log->status = 0;
                $log->type = 2;
                $log->save();
            }

        }

    }
}