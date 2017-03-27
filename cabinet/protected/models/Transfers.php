<?php

/**
 * This is the model class for table "transfers".
 *
 * The followings are the available columns in table 'transfers':
 * @property string $ID
 * @property string $date
 * @property integer $type
 * @property string $issuer
 * @property string $sourceID
 * @property string $add_info
 * @property string $targetID
 * @property integer $currency
 * @property integer $currencyN
 * @property string $amount
 * @property integer $status
 * @property string $actualamount
 * @property integer $comission
 *
 * The followings are the available model relations:
 * @property Users $issuer_
 * @property Currencies $currency_
 * @property Currencies $currencyN_
 */
class Transfers extends CActiveRecord
{
    public static $_TYPES = array (
        0 => array('account', 'Пополнение счета', ':'),
        1 => array('account', 'Внутренний перевод', ':'),
        2 => array('account', 'Конвертация') ,
        3 => array('account', 'Вывод', ':'),
        4 => array('account', 'Пополнение транзитного счета', ':'),
        5 => array('account', 'Пополнение торгового счета', ':'),
        6 => array('payment', 'Вывод средств', ':'),
        7 => array('partner', 'INTERNAL_TRANSFER', ':'),
        8 => array('partner', 'ACCOUNT_REPLENISHMENT', ':'),
        9 => array('partner', 'PAYOUT', ':'),
        10 => array('partner', 'TRADER_REPLENISHMENT', ':'),
    );

    public static $_STATUSES = array(
        0=> array('account', 'Исполнена'),
        1=> array('account', 'Принята'),
        2=> array('account', 'Отклонена'),
        3=> array('account', 'Incomplete'),
        4=> array('account', 'Failed'),
        5=> array('account', 'Отменена'),
    );

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Transfers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transfers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, type, issuer, sourceID, currency, amount, status', 'required'),
			array('type, currency, currencyN, status, comission', 'numerical', 'integerOnly'=>true),
			array('issuer, sourceID, targetID', 'length', 'max'=>10),
			array('amount, actualamount', 'length', 'max'=>16),
            array('pass', 'paypass', 'on'=>'payout'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, date, type, issuer, sourceID, targetID, currency, currencyN, amount, status, actualamount, comission, add_info', 'safe', 'on'=>'search, history'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'issuer_' => array(self::BELONGS_TO, 'Users', 'issuer'),
			'currency_' => array(self::BELONGS_TO, 'Currencies', 'currency'),
			'currencyN_' => array(self::BELONGS_TO, 'Currencies', 'currencyN'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'date' => Yii::t('account', 'Дата операции'),
			'type' => Yii::t('account', 'Описание операции'),
			'issuer' => 'Issuer',
			'sourceID' => 'Source',
			'targetID' => 'Target',
			'currency' => Yii::t('account', 'Валюта'),
			'currencyN' => 'Currency N',
			'amount' => Yii::t('account', 'Сумма'),
			'status' => Yii::t('account', 'Статус'),
			'actualamount' => 'Actualamount',
			'comission' => 'Comission',
			'add_info' => 'Содержание заявки',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('issuer',$this->issuer,true);
		$criteria->compare('sourceID',$this->sourceID,true);
		$criteria->compare('targetID',$this->targetID,true);
		$criteria->compare('currency',$this->currency);
		$criteria->compare('currencyN',$this->currencyN);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('actualamount',$this->actualamount,true);
		$criteria->compare('comission',$this->comission);
		$criteria->compare('add_info',$this->add_info,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function filter($byUser = true) {
        $criteria = new CDbCriteria();

        $criteria->with = (array('currency_','currencyN_'));

        if ($byUser) {
            $user = Users::model()->findByPk(Yii::app()->user->getId());
            $criteria->condition = 'status <> 3 AND issuer = :issuer';
            $criteria->params = array(':issuer'=>$user->ID);
        }

        $criteria->mergeWith($this->dateRangeSearchCriteria('date', $this->date));
        $criteria->compare('type',$this->type, true);
        $criteria->compare('status',$this->status, true);

        $sort = new CSort();
        $sort->defaultOrder = 'date desc';

        return new CActiveDataProvider('Transfers', array(
            'criteria' => $criteria,
            'sort' => $sort,
            'pagination' => array(
                'pageSize' => 25,
            ),
        ));
    }

    public function behaviors() {
        return array(
            'dateRangeSearch'=>array(
                'class'=>'application.components.DateRangeSearchBehavior',
            ),
        );
    }

    public function formatDate($format = 'd.m.Y G:i') {
        if ($format && is_string($format)) {
            return date ($format,strtotime($this->date));
        }
        return $this->date;
    }

    public function typeFormat() {
        $t = self::$_TYPES[$this->type];
        return sprintf('%s%s%s%s%s',
            call_user_func_array(array('Yii', 't'), $t),
            count($t) == 3 ? $t[2] : '',
            $this->sourceID > 0 ? $this->sourceID : '',
            $this->sourceID > 0 && $this->targetID > 0 ? ' -> ' : '',
            $this->targetID > 0 ? $this->targetID : ''
        );
    }

    public function formatAmount() {
        return round($this->amount, 2);
    }

    public function formatCurrency() {
        return $this->currency_['alphaCode'] . ($this->currencyN_['curID'] > 0 ? " -> {$this->currencyN_['alphaCode']}" : '');
    }

    public function formatStatus() {
        $s = self::$_STATUSES[$this->status];
        return call_user_func_array(array('Yii', 't'), $s);
        if ($this->status == 1) {
            $result .= ' <a href="#" class="cancel-btn">Отменить</a>';
        }
        return $result;
    }

    public static function getTypesList() {
        $types = array(''=>array('payment', 'Все')) + array_diff_key(self::$_TYPES, array(3=>3, 4=>4, 5=>5));
        array_walk($types, function(&$item) {
            $item = call_user_func_array(array('Yii', 't'), $item);
        });

        return $types;
    }

    public static function getStatusesList() {
        $types = array(''=>array('payment', 'Все')) + array_diff_key(self::$_STATUSES, array(3=>3, 4=>4));
        array_walk($types, function(&$item) {
                $item = call_user_func_array(array('Yii', 't'), $item);
            });

        return $types;
    }

    public function paypass($attribute,$params) {
        if(!$this->hasErrors()) {
            $user = Users::model()->findByPk($this->issuer);
            if ($user && ($user->ignorePaymentPass || $user->paymentPassword === $user->hashPassword($this->pass))) {
                //$this->pass = $user->paymentPassword;
            } else {
                $this->addError("pass", Yii::t('payout','Платежный пароль неверный'));
            }
        }
    }
}