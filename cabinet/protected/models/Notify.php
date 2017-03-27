<?php

/**
 * This is the model class for table "users_docs".
 *
 * The followings are the available columns in table 'users_docs':
 * @property string $ID
 * @property string $userID
 * @property string $docname
 * @property string $docnumber
 * @property string $docissuer
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class Notify extends CActiveRecord
{
	public $scan;
	public $notify;
	public $val;
    public $verifyCode;	
        /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UsersDocs the static model class
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
		return 'notify';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
         array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd'),'enableClientValidation'=>false, 'message' => Yii::t('messages', 'Неправильный код проверки.')),		
         array('scan', 'file', 'types'=>'jpg, gif, png', 'allowEmpty' => true, ),
			array('no, sum, date', 'required'),             
//			array('status', 'numerical', 'integerOnly'=>true),
//			array('userID', 'length', 'max'=>11),
//			array('docname, docnumber', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
//			array('ID, userID, docname, docnumber, docissuer, status', 'safe', 'on'=>'search'),
		);
	}

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'verifyCode'=>Yii::t('transfer', 'Введите код на картинке'), 
            'no'=>Yii::t('notify', 'Номер торгового счета или FxPrivate-кошелька'),
            'sum'=>Yii::t('notify', 'Сумма платежа'),       
            'date'=>Yii::t('notify', 'Дата платежа'),   
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
			'user' => array(self::BELONGS_TO, 'Users', 'userID'),
		);
	}
}