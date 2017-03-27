<?php

/**
 * This is the model class for table "ik_log".
 *
 * The followings are the available columns in table 'ik_log':
 * @property string $ID
 * @property string $ik_shop_id
 * @property string $ik_payment_amount
 * @property string $ik_payment_id
 * @property string $ik_payment_desc
 * @property string $ik_paysystem_alias
 * @property string $ik_baggage_fields
 * @property string $ik_payment_timestamp
 * @property string $ik_payment_state
 * @property string $ik_trans_id
 * @property string $ik_currency_exch
 * @property string $ik_fees_payer
 * @property string $ik_sign_hash
 * @property string $process_status
 */
class IkLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IkLog the static model class
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
		return 'ik_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ik_shop_id, ik_payment_amount, ik_payment_id, ik_payment_desc, ik_paysystem_alias, ik_baggage_fields, ik_payment_timestamp, ik_payment_state, ik_trans_id, ik_currency_exch, ik_fees_payer, ik_sign_hash, process_status', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, ik_shop_id, ik_payment_amount, ik_payment_id, ik_payment_desc, ik_paysystem_alias, ik_baggage_fields, ik_payment_timestamp, ik_payment_state, ik_trans_id, ik_currency_exch, ik_fees_payer, ik_sign_hash, process_status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'ik_shop_id' => 'Ik Shop',
			'ik_payment_amount' => 'Ik Payment Amount',
			'ik_payment_id' => 'Ik Payment',
			'ik_payment_desc' => 'Ik Payment Desc',
			'ik_paysystem_alias' => 'Ik Paysystem Alias',
			'ik_baggage_fields' => 'Ik Baggage Fields',
			'ik_payment_timestamp' => 'Ik Payment Timestamp',
			'ik_payment_state' => 'Ik Payment State',
			'ik_trans_id' => 'Ik Trans',
			'ik_currency_exch' => 'Ik Currency Exch',
			'ik_fees_payer' => 'Ik Fees Payer',
			'ik_sign_hash' => 'Ik Sign Hash',
			'process_status' => 'Process Status',
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
		$criteria->compare('ik_shop_id',$this->ik_shop_id,true);
		$criteria->compare('ik_payment_amount',$this->ik_payment_amount,true);
		$criteria->compare('ik_payment_id',$this->ik_payment_id,true);
		$criteria->compare('ik_payment_desc',$this->ik_payment_desc,true);
		$criteria->compare('ik_paysystem_alias',$this->ik_paysystem_alias,true);
		$criteria->compare('ik_baggage_fields',$this->ik_baggage_fields,true);
		$criteria->compare('ik_payment_timestamp',$this->ik_payment_timestamp,true);
		$criteria->compare('ik_payment_state',$this->ik_payment_state,true);
		$criteria->compare('ik_trans_id',$this->ik_trans_id,true);
		$criteria->compare('ik_currency_exch',$this->ik_currency_exch,true);
		$criteria->compare('ik_fees_payer',$this->ik_fees_payer,true);
		$criteria->compare('ik_sign_hash',$this->ik_sign_hash,true);
		$criteria->compare('process_status',$this->process_status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}