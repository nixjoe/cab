<?php

/**
 * This is the model class for table "transitaccounts".
 *
 * The followings are the available columns in table 'transitaccounts':
 * @property string $ID
 * @property string $userID
 * @property integer $currency
 * @property string $amount
 * @property string $opendate
 *
 * The followings are the available model relations:
 * @property Currencies $currency_
 * @property Users $user
 */
class Transitaccounts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Transitaccounts the static model class
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
		return 'transitaccounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userID, currency', 'required'),
			array('currency', 'numerical', 'integerOnly'=>true),
			array('userID', 'length', 'max'=>11),
			array('amount', 'length', 'max'=>16),
			array('opendate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, userID, currency, amount, opendate', 'safe', 'on'=>'search'),
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
			'currency_' => array(self::BELONGS_TO, 'Currencies', 'currency'),
			'user_' => array(self::BELONGS_TO, 'Users', 'userID'),		
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'userID' => 'User',
			'currency' => 'Currency',
			'amount' => 'Amount',
			'opendate' => 'Opendate',
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
		$criteria->compare('userID',$this->userID,true);
		$criteria->compare('currency',$this->currency);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('opendate',$this->opendate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}