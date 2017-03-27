<?php

/**
 * This is the model class for table "currencies".
 *
 * The followings are the available columns in table 'currencies':
 * @property integer $curID
 * @property string $name
 * @property string $alphaCode
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property Countries[] $countries_
 * @property Transfers[] $transfers_
 * @property Transitaccounts[] $transitaccounts_
 */
class Currencies extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Currencies the static model class
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
		return 'currencies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('curID, name, alphaCode, disabled', 'required'),
			array('curID, disabled', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('alphaCode', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('curID, name, alphaCode, disabled', 'safe', 'on'=>'search'),
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
			'countries_' => array(self::HAS_MANY, 'Countries', 'curID'),
			'transfers_' => array(self::HAS_MANY, 'Transfers', 'currency'),
			'transitaccounts_' => array(self::HAS_MANY, 'Transitaccounts', 'currency'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'curID' => 'Cur',
			'name' => 'Name',
			'alphaCode' => 'Alpha Code',
			'disabled' => 'Disabled',
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

		$criteria->compare('curID',$this->curID);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('alphaCode',$this->alphaCode,true);
		$criteria->compare('disabled',$this->disabled);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}