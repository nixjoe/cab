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
class MsgMessages extends CActiveRecord {

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
     * @return string the associated database table name
     */
    public function tableName() {
        return 'msg_messages';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sender, text, title', 'required'),
            array('assignee', 'required', 'on'=>'new'),
            array('thread_id', 'required', 'on'=>'reply'),
            array('thread_id', 'validThread', 'on'=>'reply', 'skipOnError'=>true),
            array('lastupdate', 'numerical', 'integerOnly' => true, 'on'=>'reply'),
            array('status', 'numerical', 'integerOnly' => true),
            array('sender', 'length', 'max' => 11),
            array('thread_id', 'length', 'max' => 20),
            array('text', 'length', 'max' => 2000),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('ID, datetime, sender, thread_id, text, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'msgAttachments_' => array(self::HAS_MANY, 'MsgAttachments', 'msg_id'),
            'thread_' => array(self::BELONGS_TO, 'MsgThreads', 'thread_id'),
            'sender_' => array(self::BELONGS_TO, 'Users', 'sender'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'ID' => 'ID',
            'datetime' => Yii::t('messages', 'Дата'),
            'sender' => Yii::t('messages', 'Отправитель'),
            'thread_id' => Yii::t('messages', 'Номер темы'),
            'text' => Yii::t('messages', 'Текст сообщения'),
            'status' => Yii::t('messages', 'Статус'),
            'assignee' => Yii::t('messages', 'Кому'),
            'title' => Yii::t('messages', 'Тема'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('ID', $this->ID, true);
        $criteria->compare('datetime', $this->datetime, true);
        $criteria->compare('sender', $this->sender, true);
        $criteria->compare('thread_id', $this->thread_id, true);
        $criteria->compare('text', $this->text, true);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    public function beforeValidate() {
        // Предварительная валидация для новой ветки
        if ($this->scenario == 'new') {
            $this->thread = new MsgThreads();
            $this->thread->assignee = $this->assignee;            
            $this->thread->client = $this->sender;
            $this->thread->title = $this->title;
            $this->thread->type = 1;
            $this->thread->status = 1;
            if (!$this->thread->validate()) {
                if (!empty($this->thread->errors['title'])){
                    $this->addError('title', $this->thread->getError('title'));
                }
                else $this->addError('thread_id','Ошибка создания сообщения');

            }
        }
        return parent::beforeValidate();
    }
    public function beforeSave() {
        $this->datetime = new CDbExpression('NOW()');
        if ($this->scenario == 'new') {
            if ($this->thread->save()) {
                $this->thread_id = $this->thread->ID;
            }
        }
        return parent::beforeSave();
    }
    
/*
 *  Валидатор для проверки, в свою ли тему пользователь добавляет ответ
 */    
    public function validThread() {
        $thread = MsgThreads::model()->findByPk($this->thread_id);
        if (
            !empty ($thread) && $thread->status == 1 &&
                (
                    $thread->client == $this->sender ||
                    $thread->assignee == $this->sender ||
                    Yii::app()->user->role <> 'user'
                )
           )
        {
            return (TRUE);
        }
        else
        {
            $this->addError('thread_id', 'You do not have a right to reply to this thread');
            return(FALSE);
        }
    }
}