<?php

/**
 * This is the model class for table "ik_shops".
 *
 * The followings are the available columns in table 'ik_shops':
 * @property integer $ID
 * @property string $shop_id
 * @property integer $cur_id
 * @property string $secret_key
 * @property string $paysystems
 *
 * The followings are the available model relations:
 * @property Countries $cur
 */
class IkShops extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IkShops the static model class
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
		return 'ik_shops';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id, cur_id, secret_key, paysystems', 'required'),
			array('cur_id', 'numerical', 'integerOnly'=>true),
			array('shop_id', 'length', 'max'=>255),
			array('secret_key', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, shop_id, cur_id, secret_key, paysystems', 'safe', 'on'=>'search'),
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
			'cur_' => array(self::BELONGS_TO, 'Countries', 'cur_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'shop_id' => 'Shop',
			'cur_id' => 'Cur',
			'secret_key' => 'Secret Key',
			'paysystems' => 'Paysystems',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('shop_id',$this->shop_id,true);
		$criteria->compare('cur_id',$this->cur_id);
		$criteria->compare('secret_key',$this->secret_key,true);
		$criteria->compare('paysystems',$this->paysystems,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}