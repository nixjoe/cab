<?php

class TransferForm extends CFormModel
{
    public $source;
    public $target;
    public $amount;
    public $verifyCode;

    public function rules()
    {
        return array(
            array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd'),'enableClientValidation'=>false),
            array('source, target, amount', 'required'),
            array('amount', 'numerical', 'min'=>0),
            array('source, target','checkConditions'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'source'=>Yii::t('transfer', 'Исходный счет'),
            'target'=>Yii::t('transfer', 'Целевой счет'),
            'amount'=>Yii::t('transfer', 'Сумма'),
            'verifyCode'=>Yii::t('transfer', 'Введите код на картинке'),
        );
    }

    public function checkConditions ($attribute,$params) {
    		$user = Users::model()->findByPk(Yii::app()->user->getId());
        if(!$this->hasErrors()){
            $s = Tradeaccounts::model()->findByAttributes(array("mtID"=>$this->source,"userID"=>Yii::app()->user->getId()));
            $t = Tradeaccounts::model()->findByAttributes(array("mtID"=>$this->target,"userID"=>Yii::app()->user->getId()));

            if (empty($s->mtID) && $this->source != $user->transitID) {
                $this->addError('source', 'Ошибка входящих данных');
            }
            if (empty($t->mtID) && $this->target != $user->transitID) {
                $this->addError('target', 'Ошибка входящих данных');
            }
        }
    }
// Нужно провести рефакторинг кода ниже.
// Проверки сишком заумные и в запутанном порядке.
// Кроме того, проверяется только баланс, не задействованный в ордерах.

    public function completeTransfer()
    {
        // 1. Проверим, принадлежат ли оба счета пользователю
        $s = Tradeaccounts::model()->findByAttributes(array("mtID"=>$this->source,"userID"=>Yii::app()->user->getId()));
        $t = Tradeaccounts::model()->findByAttributes(array("mtID"=>$this->target,"userID"=>Yii::app()->user->getId())); 
        $user = Users::model()->findByPk(Yii::app()->user->getId());
        if (empty($s) && $this->source!=$user->transitID) {
            $this->addError('source', 'Исходный кошелек не доступен');
        }
		
        if (empty($t) && $this->target!=$user->transitID) {
            $this->addError('target', 'Целевой кошелек не доступен');
        }
        $transit = Transitaccounts::model()->find(array(
                        'condition'=>'userID='.Yii::app()->user->getId().' AND currency="840"'
                        ));

        // Если нет ошибок - устlDelановим соединение с МТ (убрать, когда доступ будет осуществляться через класс:

        //if (!$this->hasErrors()) $mt = new MTconnector('78.140.130.240:443', 11, 'qwerty1');
        if (!$this->hasErrors()) {
            $mqApi = Yii::app()->params['mqApi'];
            $mt = new MTconnector($mqApi['host'] . '.' . $mqApi['port'], $mqApi['login'], $mqApi['password']);
        }

        // Проверяем наличие денег на обычном счету, если перевод идет с него:
        if (isset($mt) && $mt->connected() && ($this->source != $user->transitID)) {
            $mtinfo = $mt->find($this->source);
            if ($mtinfo['balance']<$this->amount)
                $this->addError('amount', 'Сумма превышает доступную на счету');
            // prevequity вместо баланса (?)
        }

        // Проверяем наличие денег на транзитном счету, если перевод идет с него:
        if (($this->source == $user->transitID) && ($transit->amount < $this->amount)) {
            $this->addError('amount', 'Сумма превышает доступную на счету');
        }

		
		
        if (!$this->haserrors() && isset($mt) && $mt->connected()) {

            // Переводим деньги на транзитный счет, если он не совпадает с исходным:
            if ( $this->source != $user->transitID ){
				$mt->transaction(
                        $this->source, 
                        -$this->amount, 
                        "Win-{$this->amount}:{$this->source}>{$this->target}"
                        );
				
					//// доработка на центовый счёт!!!
					$amountcent = false;
					if ($s && $t){
						if($s->attributes['fxType'] == '1' && $t->attributes['fxType'] != '1'){
							$amountcent = $this->amount / 100;
						}
					}elseif ($s && $s->attributes['fxType'] == '1'){ 	
							$amountcent  = $this->amount / 100;
					}
				$newammount = $amountcent ? $amountcent : $this->amount;
                $mt->transaction(
                        $user->transitID, 
                        $newammount,
                        "Din-{$newammount}:{$this->source}>{$user->transitID}"
                        );                        
            } else {
                // Если исходный счет транзитный - Спишем с него сумму перевода:
				
                $transit->amount = number_format(($transit->amount - $this->amount), 6, '.', '');
            }

            // Переводим деньги с транзитного счета дальше, если он не совпадает с целевым:
            if ( $this->target != $user->transitID ){
				
				
				//// доработка на центовый счёт!!!
					$amountcent = false;
					if ($s && $t){
						if ($t->attributes['fxType'] == '1' && $s->attributes['fxType'] != '1'){
							$amountcent  = $this->amount;
							$this->amount = $this->amount * 100;
						}elseif($s->attributes['fxType'] == '1' && $t->attributes['fxType'] != '1'){
							$this->amount = $this->amount / 100;
						}
					}elseif ($s && $s->attributes['fxType'] == '1'){ 	
							$this->amount = $this->amount / 100;
					}elseif ($t && $t->attributes['fxType'] == '1'){
							$amountcent  = $this->amount;
							$this->amount = $this->amount * 100;
					}
				$newammount = $amountcent ? $amountcent : $this->amount;
                $mt->transaction(
                        $user->transitID, 
                        -$newammount,
                        "Win-{$newammount}:{$user->transitID}>{$this->target}"
                        );           
                $mt->transaction(
                        $this->target, 
                        $this->amount,
					    "Din-{$this->amount}:{$this->source}>{$this->target}"
                        );                           
            } else {
					
					if ($s && $t){
						if ($t->attributes['fxType'] == '1' && $s->attributes['fxType'] != '1'){
							$this->amount = $this->amount * 100;
						}elseif($s->attributes['fxType'] == '1' && $t->attributes['fxType'] != '1'){
							$this->amount = $this->amount / 100;
						}
					}elseif ($s && $s->attributes['fxType'] == '1'){ 	
							$this->amount = $this->amount / 100;
					}elseif ($t && $t->attributes['fxType'] == '1'){	
							$this->amount = $this->amount * 100;
					}	
                // Если целевой счет совпадает с транзитным -
                $transit->amount = number_format(($transit->amount + $this->amount), 6, '.', '');
            }
	   
            $transit->save();
            
                $log = new Transfers();
                $log->issuer = Yii::app()->user->getId();
                $log->date = new CDbExpression('now()');
                $log->amount = $this->amount;
                $log->actualamount = $this->amount;
                $log->comission = 0;
                $log->currency = 840;
                $log->currencyN= 840;
                $log->sourceID = $this->source;
                $log->targetID = $this->target;
                $log->status = 0;
                $log->type = 1;
                $log->save();	    
	    
        }
    }
}