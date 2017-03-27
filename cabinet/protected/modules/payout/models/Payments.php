<?php

class Payments extends CActiveRecord
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
		return 'payments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, amount, currency, target_id, status', 'required', 'on'=>'generate'),
			array('user_id, amount, currency, status', 'required', 'on'=>'genout'),
			array('paysystem_id, paymethod_id', 'required', 'on'=>'update_system'),
			array('payee, paydate, transfer_id', 'safe'),
		);
	}

	
    public function relations()
    {
        return array(
            'system' => array(self::BELONGS_TO, 'PaySystems', 'system_id'),
        );
    }	

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'config_name' => 'Config Name',
			'name' => 'Name',
			'system_id' => 'System Id',
			'position' => 'Position',
			'enabled' => 'Enabled',
		);
	}
}