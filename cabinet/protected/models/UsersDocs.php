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
class UsersDocs extends CActiveRecord
{
	public $scan;
	public $scan1;
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
		return 'users_docs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
         array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd'),'enableClientValidation'=>false,),
         array('scan', 'file', 'types'=>'jpg, gif, png'),
			array('userID, docname, docnumber, docissuer, verifyCode', 'required'),
//			array('status', 'numerical', 'integerOnly'=>true),
//			array('userID', 'length', 'max'=>11),
//			array('docname, docnumber', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
//			array('ID, userID, docname, docnumber, docissuer, status', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'userID' => 'User',
			'docname' => Yii::t('verification', 'Название документа'),
			'docnumber' => Yii::t('verification', 'Серия и номер'),
			'docissuer' => Yii::t('verification', 'Кем выдан'),
			'status' => 'status',
			'verifyCode'=>Yii::t('verification', 'Введите код на картинке'),
            'scan' => Yii::t('verification', 'Отсканированная копия')
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
		$criteria->compare('docname',$this->docname,true);
		$criteria->compare('docnumber',$this->docnumber,true);
		$criteria->compare('docissuer',$this->docissuer,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        public function beforeSave() {
            parent::beforeSave();
            if ($this->isNewRecord) {
				foreach ($this->scan as $itm){
					if (isset($itm->tempName)){
						$data = new DataBank();
						$data->hash = base64_encode(pack('H*', sha1($this->userID . $this->docname)));
						$data->value = file_get_contents($itm->tempName);
						$data->filetype = $itm->type;
						$data->filesize = $itm->size;
						$data->filename = $itm->name;
						$data->save();
						//if ($data->save()) {return true;} else return false;
					}
				}
            }
			return true;
			
        }
}