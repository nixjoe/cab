<?php

/**
 * This is the model class for table "databank".
 *
 * The followings are the available columns in table 'databank':
 * @property string $hash
 * @property string $value
 */
class DataBank extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DataBank the static model class
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
		return 'databank';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hash, value', 'required'),
			array('hash', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('hash', 'safe', 'on'=>'search'),
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
			'hash' => 'Hash',
			'value' => 'Value',
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

		$criteria->compare('hash',$this->hash,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function afterFind() {
            $this->value = rtrim(mcrypt_decrypt(
                    MCRYPT_RIJNDAEL_256,
                    md5(Yii::app()->params['publickey']),
                    $this->value,
                    MCRYPT_MODE_CBC,
                    md5(md5(Yii::app()->params['publickey']))
                    ), "\0");
            parent::afterFind();
        }
        protected function afterValidate() {
            parent::afterValidate();
            if ($this->isNewRecord)
                $this->value = mcrypt_encrypt(
                        MCRYPT_RIJNDAEL_256,
                        md5(Yii::app()->params['publickey']),
                        $this->value,
                        MCRYPT_MODE_CBC,
                        md5(md5(Yii::app()->params['publickey']))
                        );
        }
}