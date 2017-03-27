<?php

class PartnerTransferForm extends CFormModel
{
    public $source;
    public $target;
    public $amount;
    public $password;

    public function rules() {
        return array(
            array('source, target, amount, password', 'required'),
            array('amount', 'numerical', 'min'=>0),
            array('password','checkPayPass'),
        );
    }

    public function attributeLabels() {
        return array(
            'source'=>Yii::t('transfer', 'Исходный счет'),
            'target'=>Yii::t('transfer', 'Целевой счет'),
            'amount'=>Yii::t('transfer', 'Сумма'),
            'password'=>Yii::t('payout', 'Платежный пароль'),
        );
    }

    public function checkPayPass ($attribute,$params) {
    	$user = Users::model()->findByPk(Yii::app()->user->getId());
        if(!$this->hasErrors()){
            if (!$user || $user->paymentPassword !== $user->hashPassword($this->password)) {
                $this->addError("pass", Yii::t('payout','Платежный пароль неверный'));
            }
        }
    }

    public function completeTransfer()
    {
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        //s - счет партнера, t - транзитный счет клиента
        $s = Users::getPartnerAccount($user->ID);
        $t = Transitaccounts::model()->with('user_')->find(array(
            'condition'=>"userID !='{$user->ID}' AND currency='840' AND user_.transitID='{$this->target}'"
        ));

        if (!$s || $this->source != $s->mtID) {
            $this->addError('source', 'Исходный кошелек не доступен');
        }

        if (!$t) {
            $this->addError('target', 'Целевой кошелек не доступен');
        }

        $mt = new MTconnector();
        if ($mt->connected()) {
            $mtInfo = $mt->find(array($this->source, $this->target));
            $mtSource = &$mtInfo[$this->source];
            $mtTarget = &$mtInfo[$this->target];

            if ($mtSource['balance'] < $this->amount) {
                $this->addError('amount', 'Сумма превышает доступную на счету');
            }

            if (!$this->hasErrors()) {
                $db = Yii::app()->db->beginTransaction();
                try {

                    $s->amount = number_format($mtSource['balance'] - $this->amount, 6, '.', '');
                    $t->amount = number_format($t->amount + $this->amount, 6, '.', '');
                    $s->save();
                    $t->save();

                    $time = new CDbExpression('now()');
                    $log = new Transfers();
                    $log->attributes = array(
                        'issuer' => $user->ID,
                        'date' => $time,
                        'amount' => $this->amount,
                        'actualamount' => $this->amount,
                        'comission' => 0,
                        'currency' => 840,
                        'currencyN' => 840,
                        'sourceID' => $this->source,
                        'targetID' => $this->target,
                        'status' => 0,
                        'type' => 7,
                    );
                    $log->save();

                    $log2 = new Transfers();
                    $log2->attributes = array(
                        'issuer' => $t->userID,
                        'date' => $time,
                        'amount' => $this->amount,
                        'actualamount' => $this->amount,
                        'comission' => 0,
                        'currency' => 840,
                        'currencyN' => 840,
                        'sourceID' => $this->source,
                        'targetID' => $this->target,
                        'status' => 0,
                        'type' => 8,
                    );
                    $log2->save();

                    //списываем сумму с партнерского счета
                    $mt->transaction(
                        $this->source,
                        -$this->amount,
                        "Win-{$this->amount}:{$this->source}>{$this->target}"
                    );

                    //депозит на транзитный счет партнера
                    $result = $mt->transaction(
                        $user->transitID,
                        $this->amount,
                        "Din-{$this->amount}:{$this->source}>{$user->transitID}"
                    );

                    //перевод с транзитного счета партнера на целевой транзитный счет клиента
                    $result = $mt->transaction(
                        $user->transitID,
                        -$this->amount,
                        "Win-{$this->amount}:{$user->transitID}>{$this->target}"
                    );

                    //депозит на транзитный счет клиента
                    $result = $mt->transaction(
                        $this->target,
                        $this->amount,
                        "Din-{$this->amount}:{$this->source}>{$this->target}"
                    );

                    if (!$result) {
                        throw new Exception('MT Server Error');
                    }

                    $db->commit();
                } catch (Exception $e) {
                    $db->rollback();
                }


            }
        }
    }
}