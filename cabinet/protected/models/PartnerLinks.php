<?php

/**
 * This is the model class for table "msg_messages".
 *
 * The followings are the available columns in table 'msg_messages':

 * @property string $datetime
 * @property string $sender
 * @property string $thread_id
 * @property string $text
 * @property integer $status
 *
 *
 * The followings are the available model relations:
 * @property MsgAttachments[] $msgAttachments
 * @property MsgThreads $thread
 * @property Users $sender0
 */
class PartnerLinks extends CActiveRecord {
   
    public $lnurl;
    public $value;
    public $key;
    public $order;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MsgMessages the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'partnerinfo';
    }
	
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('order',$this->order);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



}