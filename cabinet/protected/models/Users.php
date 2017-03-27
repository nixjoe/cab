<?php

/**
 * This is the model class for table "users".
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
class Users extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Users the static model class
	 */
        public $passConfirm;
        public $phonePassConfirm;
        public $birthDay;
        public $birthMonth;
        public $birthYear;
        public $dialcode;
        public $lang_iso;
       //public $birthDate;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('email, regdate, familyName, givenName, country, city, dialcode, phone, sex, birthDay, birthMonth, birthYear', 'required','on'=>'register','message'=>Yii::t('reg', 'Поле не должно быть пустым')),
            array('password, passConfirm','required','on'=>'register, reset', 'message'=>Yii::t('reg', 'Поле не должно быть пустым')),
            array('phonePassword, phonePassConfirm', 'required','on'=>'register', 'message'=>Yii::t('reg', 'Поле не должно быть пустым')),
			array('group, status, country, sex, partner, replenish_client', 'numerical', 'integerOnly'=>true),
			array('email', 'email'),
			array('password', 'match', 'pattern' => '/^([^А-Яа-я]+)*$/', 'message' => Yii::t('reg', 'Только англ. раскладка')),
            array('email','exist','on'=>'recovery', 'message'=>Yii::t('reg', 'Данный {attribute} не зарегистрирован.')),
            array('email', 'unique','on'=>'register', 'message'=>Yii::t('reg', '{attribute} "{value}" уже занят')),
            array('email, password, phonePassword, transitID, familyName, givenName, middleName, city, comment', 'length', 'max'=>32),
            array('password, phonePassword, paymentPassword', 'length', 'min'=>8, 'tooShort'=>Yii::t('reg', 'Минимум 8 символов'), 'on'=>'register, recovery'),
			array('phone', 'length', 'max'=>16),
            array('passConfirm', 'compare', 'compareAttribute'=>'password', 'on'=>'register, reset', 'message'=>Yii::t('reg', 'Пароли не совпадают')),
            array('phonePassConfirm', 'compare', 'compareAttribute'=>'phonePassword', 'on'=>'register, reset', 'message'=>Yii::t('reg', 'Пароли не совпадают')),
            array('regdate, lastdate', 'length', 'max'=>10),
            // Мы не работаем с США и их территориями:
            array('country','in','not'=>true, 'range'=>array('840','16', '316', '580', '630', '850', '581'),'message'=>Yii::t('reg', 'Регистрация в этой стране запрещена')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
            array('password, verifyCode', 'safe', 'on'=>'reset'),
            array('subscribe', 'safe', 'on'=>'register, update'),
            array('ignorePaymentPass, birthDate, hb_year_sent', 'safe', 'on'=>'update'),

			array('ID, email, password, group, status, regdate, lastdate, transitID, familyName, givenName, middleName, country, city, phone, comment, sex, birthDate', 'safe', 'on'=>'search'),
            /////////////////////////////////// original
			//array('email, password, group, status, regdate, lastdate, transitID, name, country, city, zipcode, address, phone, comment, sex', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
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
                        'msgMessages_' => array(self::HAS_MANY, 'MsgMessages', 'sender'),
                        'msgThreads_' => array(self::HAS_MANY, 'MsgThreads', 'client'),
						'tradeaccounts_' => array(self::HAS_MANY, 'Tradeaccounts', 'userID'),
						'transitaccounts_' => array(self::HAS_MANY, 'Transitaccounts', 'userID'),
                        'transfers_'=>array(self::HAS_MANY,'Transfers','issuer'),
                        'country_' => array(self::BELONGS_TO, 'Countries', 'country'),
                        'payoutcredentials_' => array(self::HAS_MANY, 'Payoutcredentials', 'userID'),
                        'usersManagers_' => array(self::HAS_ONE, 'UsersManagers', 'userID'),
                        'usersDocs' => array(self::HAS_MANY, 'UsersDocs', 'userID')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'email' => 'Email',
			'password' => 'Пароль',
            'phonePassword' => 'Телефонный пароль',
            'paymentPassword'=> 'Платежный пароль',
			'group' => 'Группа',
			'status' => 'Статус',
			'regdate' => 'Дата регистрации',
			'lastdate' => 'Дата последнего входа',
			'transitID' => 'Транзитный аккаунт',
			'familyName'=>'Фамилия',
            'givenName' => 'Имя',
            'middleName' => 'Отчество',
            'birthDate' => 'Дата рождения',
            'birthDay'=> 'Число',
            'birthMonth'=> 'Месяц',
            'birthYear'=> 'Год',
			'country' => 'Страна',
			'city' => 'Город',
			'phone' => 'Телефон',
			'comment' => 'Комментарий',
			'sex' => 'Пол',
            'passConfirm'=>'Подтверждение пароля',
            'phonePassConfirm'=>'Подтверждение телефонного пароля',
            'phonePassConfirm'=>'Подтверждение телефонного пароля',
            'subscribe' => 'Cогласен получать новости компании по почте',
            'ignorePaymentPass' => 'Не проверять платежный пароль',
            'replenish_client' => 'Внутренние переводы Партнера'
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

		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
                $criteria->compare('phonePassword',$this->phonePassword,true);
		$criteria->compare('group',$this->group);
		$criteria->compare('status',$this->status);
		$criteria->compare('regdate',$this->regdate,true);
		$criteria->compare('lastdate',$this->lastdate,true);
		$criteria->compare('transitID',$this->transitID,true);
		$criteria->compare('familyName',$this->familyName,true);
                $criteria->compare('givenName',$this->givenName,true);
                $criteria->compare('middleName',$this->middleName,true);
                $criteria->compare('birthDate',$this->middleName,true);
		$criteria->compare('country',$this->country);
		$criteria->compare('city',$this->city,true);
//		$criteria->compare('zipcode',$this->zipcode,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('comment',$this->comment,true);

		$criteria->compare('ID',$this->ID,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
                $criteria->compare('phonePassword',$this->phonePassword,true);
		$criteria->compare('paymentPassword',$this->paymentPassword,true);
		$criteria->compare('group',$this->group);
		$criteria->compare('status',$this->status);
		$criteria->compare('regdate',$this->regdate,true);
		$criteria->compare('lastdate',$this->lastdate,true);
		$criteria->compare('transitID',$this->transitID,true);
		$criteria->compare('familyName',$this->familyName,true);
		$criteria->compare('givenName',$this->givenName,true);
		$criteria->compare('middleName',$this->middleName,true);
		$criteria->compare('birthDate',$this->birthDate,true);
		$criteria->compare('country',$this->country);
		$criteria->compare('city',$this->city,true);
         $criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('sex',$this->sex);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		    'pagination' => array(
		        'pageSize' => 20,
		    ),			
		));
	}

  protected function beforeSave()
    {
		$scenario = $this->getScenario();
		// Insert the new user into the database. We do this now to get the last inserted id for later use.
		if ( $scenario == 'reset'  )
		{
			$this->password = $this->hashPassword($this->password);
            $this->verifyCode = null;
                        //$this->phonePassword = $this->hashPassword($this->phonePassword);
		}
		elseif ( in_array( $scenario, array('register', 'recovery') ))
		{
			$this->password = $this->hashPassword($this->password);
                        $this->passConfirm = $this->hashPassword($this->passConfirm);
			//$this->phonePassword = $this->hashPassword($this->phonePassword);
                        //$this->phonePassConfirm = $this->hashPassword($this->phonePassConfirm);
		}
                elseif ($scenario == 'paymentPassStore') {
                    $this->paymentPassword = $this->hashPassword($this->paymentPassword);
                }
                if ($scenario == 'register') {
                    //$this->birthDate = new CDbExpression('STR_TO_DATE(' . $this->birthYear . '-' . $this->birthDay . '-' . $this->birthMonth . ')');
                    $this->birthDate = $this->birthYear . '-' . $this->birthMonth . '-' . $this->birthDay;
                    $this->phone = $this->dialcode . $this->phone;
                }
		//$this->activkey = sha1(microtime().$this->password);
		return parent::beforeSave();
    }
    	public function hashPassword($pwd)
	{
		return base64_encode(pack('H*', sha1($pwd)));
	}

    public static function generatePassword($length=10) {
        $chars = array_merge(range(0,9), range('a','z'), range('A','Z'));
        shuffle($chars);
        return implode(array_slice($chars, 0, $length));
    }

    public static function getPartnerAccount($userId) {
        return Tradeaccounts::model()->with('fxType_')->find(
            array('condition'=>'userID=\''.$userId.'\' AND fxType_.mtGroup=\'Partner\'')
        );
    }

    public static function getPartnerAccountId($userId) {
        $account = self::getPartnerAccount($userId);
        return $account ? $account->mtID : null;
    }

    public static function getUserByPartnerAcc($ref) {
        $account = Tradeaccounts::model()->with('fxType_', 'user_')->find(
            array('condition'=>"fxType_.mtGroup='Partner' AND mtID='".intval($ref)."'")
        );
        return $account ? $account->user_ : null;
    }

    public static function getPartnerAccountByTransitID($tId) {
        return Tradeaccounts::model()->with('fxType_', 'user_')->find(
            array('condition'=>'user_.transitID=\''.$tId.'\' AND fxType_.mtGroup=\'Partner\'')
        );
    }

    public static function getAgentAccount($mtId) {
        return Tradeaccounts::model()->with('fxType_')->find(
            array('condition'=>'mtID=\''.intval($mtId).'\' AND fxType_.mtGroup=\'Partner\'')
        );
    }
}