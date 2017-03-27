<?php

/**
 * This is the model class for table "payoutcredentials".
 *
 * The followings are the available columns in table 'payoutcredentials':
 * @property string $ID
 * @property string $userID
 * @property integer $payoutmethodID
 * @property string $accountnumber
 * @property integer $papers
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Payoutmethods $payoutmethod
 */
class Payoutcredentials extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Payoutcredentials the static model class
     */

    public $uploadPapers;
    private $required;
    public $status;
    public $verifyCode;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'payoutcredentials';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
         	array('verifyCode', 'captcha', 'allowEmpty'=>!extension_loaded('gd'),'enableClientValidation'=>false),
            array('userID, payoutmethodID, accountnumber', 'required'),
            array('payoutmethodID, date, papers', 'numerical', 'integerOnly'=>true),
            array('userID', 'length', 'max'=>11),
            array('accountnumber', 'length', 'max'=>32),
            array('uploadPapers', 'checkPapers'),
            array('accountnumber', 'checkAccNo'),
            array('uploadPapers', 'file', 'types'=>'jpg, gif, png', 'allowEmpty' => TRUE),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('ID, date, userID, payoutmethodID, accountnumber, papers', 'safe', 'on'=>'search'),
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
            //'payoutmethods_' => array(self::BELONGS_TO, 'Payoutmethods', 'payoutmethodID'),
			'payoutmethods_' => array(self::BELONGS_TO, 'Payoutmethods', 'payoutmethodID', 'condition'=>'enabled=1'),
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
            'payoutmethodID' => Yii::t('payout', 'Способ вывода'),
            'accountnumber' => Yii::t('payout', 'Номер счета в выбранной системе'),
            'uploadPapers' => Yii::t('payout', 'Сопроводительные документы'),
            'status' => Yii::t('payout', 'Статус'),
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
        $criteria->compare('payoutmethodID',$this->payoutmethodID);
        $criteria->compare('accountnumber',$this->accountnumber,true);
        $criteria->compare('papers',$this->papers);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    /*
     * Валидатор для проверки, нужы ли доки:
     */
    public function checkPapers() {
        if (empty($this->payoutmethodID)){
            $this->addError('uploadPapers', Yii::t('messages', 'Поле не должно быть пустым'));
            return false;
        } else {
            $this->required = Payoutmethods::model()->findByPk($this->payoutmethodID)->papers_required == 1;
            if (empty($this->uploadPapers) == $this->required) {
                $this->addError('uploadPapers', Yii::t('messages', 'Данный способ выплаты требует загрузки отсканированных копий сопроводительных документов'));
                return false;
            }
        }
        return true;
    }

    public function checkAccNo() {
        if (empty($this->payoutmethodID)){
            $this->addError('accountnumber', Yii::t('messages', 'Поле не должно быть пустым'));
            return false;
        } else {
        		$accNo = $this->accountnumber;
        		switch($this->payoutmethodID) {
					case 2:
						$template = "/^z\d{12}$/i";
						break;
					case 3:
						$template = "/^r\d{12}$/i";
						break;
					case 4:
						$template = "/^u\d{12}$/i";
						break;
					case 6:
						$template = "/^u\d{7}$/i";
						break;
					case 7:
						$template = "/^\d{12}$/i";
						break;
					case 8:
						$template = "/^(\S+)@([a-z0-9-]+)(\.)([a-z]{2,4})(\.?)([a-z]{0,4})+$/";
						break;
					case 9:
						$template = "/^\d{12}$/i";
						//$template = "/^38\d{10}$/i";
						break;
					case 10:
						$template = "/^\d{8}$/i";
						break;
					case 11:
						$template = "/^\d{10,11}$/i";
						break;						
					case 12:
						$template = "/^\d{16}$/i";
						break;
					case 13:
						$template = "/^\d{16}$/i";
						break;
					case 15:
						$template = "/^\d{16}$/i";
						break;
					case 16:
						$template = "/^([EU]){1}\d{7}$/i";
						break;
                    case 17:
                        $template = "/^FP\d{5}$/i";
                        break;
					default:
						$template = "/./";
				}
				$err = preg_match ($template, $accNo);
				if(!$err) $this->addError('accountnumber', Yii::t('messages', 'Неправильный номер счета'));
        }
        return true;
    }

        public function beforeSave() {
            parent::beforeSave();
            if ($this->isNewRecord && !empty($this->uploadPapers)) {
                $data = new DataBank();
                $data->hash = base64_encode(pack('H*', sha1($this->userID . $this->payoutmethodID)));
                if(is_array($this->uploadPapers)) {
                    foreach($this->uploadPapers as $flname) {
                        $data->value = file_get_contents($flname->tempName);
                        if ($data->save()) {var_dump($data->errors); return true;} else return false;
                    }
                } else {
                    $data->value = file_get_contents($this->uploadPapers->tempName);
                    if ($data->save()) {var_dump($data->errors); return true;} else return false;
                }
            } else return true;
        }
}