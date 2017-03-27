<?php

/**
 * This is the model class for table "paymethods".
 *
 * @property integer $ID
 * @property string  $config_name
 * @property string  $name
 * @property integer $system_id
 * @property integer $position
 * @property integer $enabled
 *
 */
class PayMethod extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return PayMethod the static model class
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
        return 'paymethods';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('ID', 'safe', 'on'=>'search'),
        );
    }



    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'id',
            'config_name' => 'config_name',
            'name' => 'name',
            'system_id' => 'system_id',
            'position' => 'position',
            'enabled' => 'enabled',

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
        $criteria->compare('name',$this->name,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


    public function relations()
    {
        return array(
            'paycurrency' => array(self::HAS_MANY, 'Paycurrency', 'method_id')
        );
    }



}