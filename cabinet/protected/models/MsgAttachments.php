<?php

/**
 * This is the model class for table "msg_attachments".
 *
 * The followings are the available columns in table 'msg_attachments':
 * @property string $ID
 * @property string $msg_id
 * @property string $type
 * @property string $ext_id
 *
 * The followings are the available model relations:
 * @property MsgMessages $msg
 */
class MsgAttachments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return MsgAttachments the static model class
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
		return 'msg_attachments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('msg_id, type, ext_id', 'required'),
			array('msg_id, ext_id', 'length', 'max'=>20),
			array('type', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, msg_id, type, ext_id', 'safe', 'on'=>'search'),
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
			'msg' => array(self::BELONGS_TO, 'MsgMessages', 'msg_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'msg_id' => 'Msg',
			'type' => 'Type',
			'ext_id' => 'Ext',
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
		$criteria->compare('msg_id',$this->msg_id,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('ext_id',$this->ext_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}