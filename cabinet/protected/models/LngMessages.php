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
class LngMessages extends CActiveRecord {

    public $title;
    public $assignee;
    public $lastupdate;
    private $thread; // Переменная, содержащая ветку.

    public $newCategory;

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
        return 'sourcemessage';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category, message', 'required'),
            array('newCategory', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'lngTranslate_' => array(self::HAS_MANY, 'LngTranslations', 'id'),
        );
    }
}