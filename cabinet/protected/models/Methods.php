<?php

/**
 * This is the model class for table "payoutmethods".
 *
 * @property string $ID
 * @property string $email
 * @property string $password
 * @property string $phonePassword
 * @property string $paymentPassword
 * @property integer $group
 * @property integer $status
 * @property string $regdate
 * @property string $lastdate
 * @property string $transitID
 * @property string $middleName
 * @property string $familyName
 * @property string $givenName
 * @property string $birthDate
 * @property integer $country
 * @property string $city
 * @property string $address
 * @property string $phone
 * @property string $comment
 * @property integer $sex
 *
 * The followings are the available model relations:
 * 
 * @property MsgMessages[] $msgMessages_
 * @property MsgThreads[] $msgThreads_
 * @property Payoutcredentials[] $payoutcredentials_
 * @property Tradeaccounts[] $tradeaccounts_
 * @property Transfers[] $transfers_
 * @property Transitaccounts[] $transitaccounts_
 * @property Countries $country_
 * @property UsersManagers[] $usersManagers_
 * 
 */
class Methods extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Users the static model class
	 */
        //public $name;
       

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'payoutmethods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('name', 'required','on'=>'register','message'=>'Поле не должно быть пустым'),
                        
			array('ID', 'safe', 'on'=>'search'),
                  
		);
	}

	

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'name' => 'Название',
			
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