<?php

class MsgMessagesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$connection=Yii::app()->db;
		$sql = "SELECT status as status FROM  msg_messages WHERE  id = $id";
		$cnt = $connection->createCommand($sql)->queryRow();
		if ($cnt['status'] == 0){
			$sql = "UPDATE msg_messages SET  status = 1	WHERE  id = $id";
			$connection->createCommand($sql)->execute();
		}
		
		if(isset($_POST['send_msg'])) {
		
		    
			
			/*$model1 = new MsgMessages('reply');    
            $model1->attributes = $_POST['send_msg'];
            $model1->sender = $this->user->ID;
		
			$model1->save();*/
		   /*$sql = 'INSERT INTO `msg_threads`  SET
						`type` = \'1\' ,
						`status` = \'1\' ,
						`client` = \''.$user.'\',
						`title` = \''.$subject.'\',
						`assignee` = \''.$user.'\'
						 ';
		    $connection->createCommand($sql)->execute();
		    $sql = 'SELECT LAST_INSERT_ID() as id FROM `msg_threads` ';
			
		    $th_id = $connection->createCommand($sql)->queryRow();
			*/
	
			
		    $sql = 'INSERT INTO `msg_messages`  SET
						`datetime` = \''.date('Y-m-d H:i', time()).'\',
						`sender` = \''.$_POST['send_msg']['sender'].'\' ,
						`text` = '.$connection->quoteValue($_POST['send_msg']['text']).',
						`status` = \'1\',
						`thread_id` = \''.$_POST['send_msg']['thread_id'].'\'
						 ';
			
		    $connection->createCommand($sql)->execute();
			
		}
		
		$managers = UsersManagers::model()->findAll();
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'managers' => $managers
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new MsgMessages;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['MsgMessages']))
		{
			$model->attributes=$_POST['MsgMessages'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('create',array(
			'model'=>$model,
			
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['MsgMessages']))
		{
			$model->attributes=$_POST['MsgMessages'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$userauth = array(0=>91, 1=>27, 2=>29);
			$id = Yii::app()->user->id;
			if(!in_array($id, $userauth)){
				$this->redirect(YII::app()->createUrl("site/login"));
		}
			
			
			$dataProvider = new CActiveDataProvider('MsgMessages', array(
                'sort'=>array(
                    'defaultOrder'=>'datetime DESC',
                ),
                'pagination'=>array(
                    'pageSize'=>40
                ),
            ));
			$this->render('index',array(
				'dataProvider'=>$dataProvider,
				
			));
		
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new MsgMessages('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['MsgMessages']))
			$model->attributes=$_GET['MsgMessages'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=MsgMessages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='msg-messages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
