<?php

/**
 * This is the model class for table "paycurrency".
 *
 * @property integer $method_id
 * @property integer $currency_id
 * @property integer $enabled
 *
 */
class PayCurrency extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return PayCurrency the static model class
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
        return 'paycurrency';
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
            'method_id' => 'method_id',
            'currency_id' => 'currency_id',
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

}