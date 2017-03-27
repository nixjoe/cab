<?php

/**
 * This is the model class for table "msg_threads".
 *
 * The followings are the available columns in table 'msg_threads':
 * @property string $ID
 * @property string $title
 * @property string $client
 * @property string $assignee
 * @property integer $status
 * @property integer $type
 *
 * The followings are the available model relations:
 * @property MsgMessages[] $msgMessages
 * @property MsgThreadTypes $type0
 * @property Users $client0
 */
class MsgThreads extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MsgThreads the static model class
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
		return 'msg_threads';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('client', 'required'),
			array('status, type', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, title, client, assignee, status, type', 'safe', 'on'=>'search'),
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
			'msgMessages_' => array(self::HAS_MANY, 'MsgMessages', 'thread_id'),
			'type_' => array(self::BELONGS_TO, 'MsgThreadTypes', 'type'),
			'client_' => array(self::BELONGS_TO, 'Users', 'client'),
                        'msg_count'=>array(self::STAT, 'MsgMessages', 'thread_id', 'condition'=>'`status` <> "2"'),
                        'msg_unread_count'=>array(
                            self::STAT,
                            'MsgMessages', 
                            'thread_id', 
                            'condition'=>'`status` = "1" AND `sender` <> :sender', 
                            'params'=>array(':sender'=>Yii::app()->user->getId())
                            ),
                        'msg_max_id'=>array (
                            self::STAT,
                            'MsgMessages',
                            'thread_id',
                            'select'=>'MAX(`ID`)'
                        )
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'title' => Yii::t('messages', 'Тема'),
			'client' => 'Client',
			'assignee' => 'Assignee',
			'status' => 'Status',
			'type' => 'Type',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('client',$this->client,true);
		$criteria->compare('assignee',$this->assignee,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('type',$this->type);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}