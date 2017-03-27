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
class Languages extends CActiveRecord
{
	public $title;
	public $iso;
	public $name;
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
		return 'languages';
	}

    public function attributeLabels()
    {
        return array(
            'name'=>'Название(рус.)',
            'title'=> 'Название(которое видно пользователям)',
            'iso'=> 'Код',
            'sort'=> 'Позиция',
            'active'=> 'Активно',            
        );
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, name, iso, active, sort','safe'),        
		);
	}

}