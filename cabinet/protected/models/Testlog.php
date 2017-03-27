<?php

/**
 * This is the model class for table "testlog".
 *
 * The followings are the available columns in table 'testlog':
 * @property integer $id
 * @property integer $sizetest
 * @property integer $datatest
 * @property integer $status
 * @property double $exectime
 */
class Testlog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Testlog the static model class
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
		return 'testlog';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sizetest, datatest, status, exectime', 'required'),
			array('sizetest, datatest, status', 'numerical', 'integerOnly'=>true),
			array('exectime', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sizetest, datatest, status, exectime', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'sizetest' => 'Sizetest',
			'datatest' => 'Datatest',
			'status' => 'Status',
			'exectime' => 'Exectime',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('sizetest',$this->sizetest);
		$criteria->compare('datatest',$this->datatest);
		$criteria->compare('status',$this->status);
		$criteria->compare('exectime',$this->exectime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}