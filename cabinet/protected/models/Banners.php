<?php

/**
 * This is the model class for table "banners".
 *
 * The followings are the available columns in table 'banners':
 * @property integer $id
 * @property string $url
 * @property string $content
 * @property integer $position
 * @property integer $status
 * @property string $language
 */
class Banners extends CActiveRecord
{
    const STATUS_NONE = 0;
    const STATUS_ALL = 1;
    const STATUS_PARTNER = 2;
    const STATUS_NOT_PARTNER = 3;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Banners the static model class
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
		return 'banners';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url, content', 'required'),
			array('position, status', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>255),
			array('language', 'length', 'max'=>7),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, url, content, position, status, language', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'url' => 'Ссылка',
			'content' => 'Баннер',
			'position' => 'Порядок',
			'status' => 'Статус',
			'language' => 'Язык',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('status',$this->status);
		$criteria->compare('language',$this->language,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 't.position asc',
            ),
		));
	}

    public static function statusList() {
        return array(
            self::STATUS_NONE => 'Не показывать',
            self::STATUS_ALL => 'Для всех пользователей',
            self::STATUS_PARTNER => 'Только для партнеров',
            self::STATUS_NOT_PARTNER => 'Только для НЕ партнеров'
        );
    }

    public static function statusTitle($status) {
        $list = self::statusList();
        return array_key_exists($status, $list) ? $list[$status] : $status;
    }
}