<?php

/**
 * This is the model class for table "msg_messages".
 *
 * The followings are the available columns in table 'msg_messages':
 * @property string $ID
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
class LngTranslations extends CActiveRecord {

    public $title;
    public $assignee;
    public $lastupdate;
    private $thread; // Переменная, содержащая ветку.
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MsgMessages the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('translation, language', 'safe'),
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'message';
    }


    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'lngTranslate_' => array(self::BELONGS_TO, 'LngMessages', 'id'),
        );
    }
}