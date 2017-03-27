<?php

/**
 * This is the model class for table "fxtypes".
 *
 * The followings are the available columns in table 'fxtypes':
 * @property integer $ID
 * @property string $name
 * @property string $leverage
 * @property string $mtGroup
 * @property integer $disabled
 *
 * The followings are the available model relations:
 * @property Tradeaccounts[] $tradeaccounts_
 */
class Fxtypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Fxtypes the static model class
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
		return 'fxtypes';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, leverage, mtGroup', 'required'),
			array('disabled', 'numerical', 'integerOnly'=>true),
			array('name, leverage', 'length', 'max'=>32),
			array('mtGroup', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, name, leverage, mtGroup, disabled', 'safe', 'on'=>'search'),
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
			'tradeaccounts_' => array(self::HAS_MANY, 'Tradeaccounts', 'fxType'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'name' => 'Name',
			'leverage' => 'Leverage',
			'mtGroup' => 'Mt Group',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('leverage',$this->leverage,true);
		$criteria->compare('mtGroup',$this->mtGroup,true);
		$criteria->compare('disabled',$this->disabled);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function leverageList() {
        $values = explode(",", $this->leverage);
        $list = array();
        foreach ($values as $v) {
            $list[$v] = '1:'.$v;
        }

        return $list;
    }
}