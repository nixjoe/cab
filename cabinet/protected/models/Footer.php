<?php

/**
 * This is the model class for table "footer".
 *
 * The followings are the available columns in table 'footer':
 * @property integer $id
 * @property string $top_links
 * @property string $pay_systems
 * @property string $menu
 * @property string $soc_buttons
 * @property string $language
 * @property integer $publish
 */
class Footer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Footer the static model class
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
		return 'footer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('top_links, pay_systems, menu, soc_buttons', 'safe'),
			array('publish', 'numerical', 'integerOnly'=>true),
			array('language', 'length', 'max'=>3),
            array('language','unique'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, top_links, pay_systems, menu, soc_buttons, language, publish', 'safe', 'on'=>'search'),
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
			'top_links' => 'Ccылки (вверху)',
			'pay_systems' => 'Платежные системы',
			'menu' => 'Меню',
			'soc_buttons' => 'Иконки соц сетей',
			'language' => 'Язык',
			'publish' => 'Опубликовано',
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
		$criteria->compare('top_links',$this->top_links,true);
		$criteria->compare('pay_systems',$this->pay_systems,true);
		$criteria->compare('menu',$this->menu,true);
		$criteria->compare('soc_buttons',$this->soc_buttons,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('publish',$this->publish);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}