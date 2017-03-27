<?php

class MessagesController extends Controller
{
    public $user;
    public $types;
    public $thread_count;
    public $filter;

    public function __construct($id, $module = null) {
        $this->layout = 'messages';
        parent::__construct($id, $module);
        $this->types = MsgThreadTypes::model()->findAll(['with' => 'thread_count']);
        //$this->thread_count = MsgThreads::model()->count(array('condition'=>'status <> 2'));
        $connection = Yii::app()->db;
        $sql = 'SELECT COUNT(m.`id`)  as cnt FROM `msg_messages` m LEFT JOIN `msg_threads` t ON m.`thread_id` = t.`ID` WHERE t.`client` = \''
            . $this->user->ID . '\' AND t.`status` IN (\'0\',\'1\')  AND m.`status` IN (\'0\',\'1\')';
        $cnt = $connection->createCommand($sql)->queryRow();
        $this->thread_count = $cnt['cnt'];
    }

    public function render($view, $data = null, $return = false, $scripts = false) {
        if (Yii::app()->request->isAjaxRequest) {
            CController::renderPartial($view, $data, $return, $scripts);
        } else {
            CController::render($view, $data, $return);
        }
    }

    public function actionIndex($page = 0, $filter = null) {
        $perpage = 10;
        $criteria = new CDbCriteria();
        $criteria->limit = $perpage;
        $criteria->offset = $page * $perpage;
        $criteria->together = true;
        $criteria->group = '`msgMessages_`.`thread_id`';
        $criteria->order = 'max(`msgMessages_`.`datetime`) DESC';
        $criteria->addCondition('client = :client');
        $criteria->join = '';
        $criteria->select = '
                        `ID`, 
                        `title`, 
                        `status`, 
                        `type`,
                        count(`msgMessages_`.`ID`) as msg_count';
        $criteria->params = [
            ':client' => $this->user->ID,
        ];
        $criteria->with = [
            'msgMessages_'
        ];
        $countCriteria = new CDbCriteria();
        $countCriteria->condition = 'client = :client';
        $countCriteria->params = [':client' => $this->user->ID];
        if (!empty ($filter)) {
            $criteria->with = array_merge(
                $criteria->with,
                [
                    'type_' => [
                        'select'    => false,
                        'condition' => 'slug = :slug'
                    ]
                ]
            );
            $criteria->params = array_merge(
                $criteria->params,
                [
                    ':slug' => $filter
                ]
            );
            $countCriteria->with = [
                'type_' => [
                    'select'    => false,
                    'condition' => 'slug = :slug'
                ]
            ];
            $countCriteria->params = array_merge(
                $criteria->params,
                [
                    ':slug' => $filter
                ]
            );
            $this->filter = MsgThreadTypes::model()->find('slug = :slug', [':slug' => $filter]);
        }
        $threads = MsgThreads::model()->findAll($criteria);
        $thread_count = MsgThreads::model()->count($countCriteria);
        $replymessage = new MsgMessages();
        $loadmore = ($thread_count > count($threads) + ($page) * $perpage);
        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('index', [
                'threads'      => $threads,
                'replymessage' => $replymessage,
                'loadmore'     => $loadmore,
                'page'         => $page,
            ], false, true
            );
        } else {
            $this->render('index', [
                    'threads'      => $threads,
                    'replymessage' => $replymessage,
                    'loadmore'     => $loadmore,
                    'page'         => $page,
                ]
            );
        }
    }

    public function actionLoadThread($thread_id, $page = 0) {
        if (Yii::app()->request->isAjaxRequest) {
            $thread = MsgThreads::model()->findByPk($thread_id, ['condition' => "client = :client", 'params' => [':client' => $this->user->ID]]);
            $messages = $thread->msgMessages_();
            $this->renderPartial('thread', [
                'messages'  => $messages,
                'thread_id' => $thread_id,
            ], false, (int)Yii::app()->request->getPost('firstajax'));
            if (!empty($thread)) {
                MsgMessages::model()->updateAll(
                    ['status' => '0'], "`thread_id` = :thread_id AND `status`= '1' AND `sender` <> :sender", [
                    ':thread_id' => $thread_id, ':sender' => $this->user->ID
                ]);
            }
        } else {
            $this->redirect(YII::app()->createUrl("messages"));
        }
    }

    public function actionCreate() {
        $model = new MsgMessages('new');
        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && $_POST['ajax']==='msg-messages-index-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */
        $managers = UsersManagers::model()->findAll();
        if (isset($_POST['MsgMessages'])) {
            $model->attributes = $_POST['MsgMessages'];
            $model->sender = $this->user->ID;
            if ($model->validate()) {
                $model->save();
                $subject = "Новое сообщение";
                $from = $this->user->email;
                $connection = Yii::app()->db;
                $sql = 'SELECT LAST_INSERT_ID() as id FROM msg_messages ';
                $messid = $connection->createCommand($sql)->queryRow();
                if (isset($messid['id'])) {
                    $text = 'Имя пользователя: ' . $this->user->familyName . " " . $this->user->givenName . " " . $this->user->middleName . "<br>";
                    $text .= '№ сообщения - ' . $messid['id'] . ' ';
                    $to = "support@fx-private.com";
                    $headers = 'From: support@fx-private.com' . "\r\n" .
                        'Reply-To: support@fx-private.com' . "\r\n" .
                        'Content-type: text/html; charset="utf-8"' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    $r = mail($to, $subject, $text, $headers);
                }
                $this->redirect(['/messages']);
            } else {
                Yii::app()->user->setFlash('error', [
                    'header' => Yii::t('alert', "Необходимо исправить следующие ошибки:"),
                    'text'   => CHtml::errorSummary($model, '', '')
                ]);
            }
        }
        $this->render('create', [
                'model'    => $model,
                'managers' => $managers,
            ]
        );
    }

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
    public function actionReply() {
        if (isset($_POST['MsgMessages'])) {
            $model = new MsgMessages('reply');
            $model->attributes = $_POST['MsgMessages'];
            $model->sender = $this->user->ID;
            $thread_id = $model->thread_id;
            $thread_valid = $model->validate('thread_id');
            if ($model->validate()) {
                $model->save();
                $subject = "Новое сообщение";
                $from = $this->user->email;
                $connection = Yii::app()->db;
                $sql = 'SELECT LAST_INSERT_ID() as id FROM msg_messages ';
                $messid = $connection->createCommand($sql)->queryRow();
                if (isset($messid['id'])) {
                    $text = 'Имя пользователя: ' . $this->user->familyName . " " . $this->user->givenName . " " . $this->user->middleName . "<br>";
                    $text .= '№ сообщения - ' . $messid['id'] . ' ';
                    $to = "support@fx-private.com";
                    $headers = 'From: support@fx-private.com' . "\r\n" .
                        'Reply-To: support@fx-private.com' . "\r\n" .
                        'Content-type: text/html; charset="utf-8"' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
                    $r = mail($to, $subject, $text, $headers);
                }
                //$this->successflash = 'Сообщение отправлено';
                Yii::app()->user->setFlash('successflash', [
                    'header' => Yii::t('alert', "Сообщение отправлено"),
                    'text'   => CHtml::errorSummary($model, '', '')
                ]);
                $lastupdate = $model->lastupdate;
                $last_msg = $model->ID;
                $model = new MsgMessages();
            } else {
                Yii::app()->user->setFlash('error', [
                    'header' => Yii::t('alert', "Необходимо исправить следующие ошибки:"),
                    'text'   => CHtml::errorSummary($model, '', '')
                ]);
            }
            $messages = [];
            if ($thread_valid) {
                $messages = MsgThreads::model()->findByPk($thread_id)->msgMessages_();
                $criteria = new CDbCriteria;
                $criteria->addCondition('thread_id = :thread_id');
                $criteria->addCondition('ID > :lastupdate');
                $criteria->params = [
                    ':thread_id'  => $thread_id,
                    ':lastupdate' => $lastupdate,
                ];
                $messages = MsgMessages::model()->findAll($criteria);
            }
            $newcount = MsgMessages::model()->count(
                'thread_id = :thread_id',
                [':thread_id' => $thread_id]);
            $this->renderPartial('thread', [
                'messages'     => $messages,
                'replymessage' => $model,
                'thread_id'    => $thread_id,
                'newcount'     => $newcount
            ]);
        } else {
            die();
        }
    }
}