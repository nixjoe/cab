<?php

/**
 * This is the model class for table "tradeaccounts".
 *
 * The followings are the available columns in table 'tradeaccounts':
 * @property string $ID
 * @property string $userID
 * @property string $mtID
 * @property integer $fxType
 * @property integer $leverage
 * @property string $amount
 *
 * The followings are the available model relations:
 * @property Fxtypes $fxType_
 * @property Users $user_
 */
class Tradeaccounts extends CActiveRecord
{
    public $verifyCode;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Tradeaccounts the static model class
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
		return 'tradeaccounts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userID, fxType, leverage', 'required'),
                        array('mtID','required','on'=>'save'),
			array('fxType, leverage', 'numerical', 'integerOnly'=>true),
			array('userID, mtID', 'length', 'max'=>11),
			array('amount', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, userID, mtID, fxType, leverage, amount', 'safe', 'on'=>'search'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd'),'enableClientValidation'=>false, 'on'=>'secure'),
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
			'fxType_' => array(self::BELONGS_TO, 'Fxtypes', 'fxType'),
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
			'mtID' => 'Mt',
			'fxType' => 'Fx Type',
			'leverage' => 'Leverage',
			'amount' => 'Amount',
            'verifyCode'=>Yii::t('transfer', 'Введите код на картинке'),
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
		$criteria->compare('mtID',$this->mtID,true);
		$criteria->compare('fxType',$this->fxType);
		$criteria->compare('leverage',$this->leverage);
		$criteria->compare('amount',$this->amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}