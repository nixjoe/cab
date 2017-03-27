<?php

class MailTemplatesController extends Controller
{
	/*
         *
         * Данная функция генерирует ассоциативный массив для списка родительских страниц:
         *
         */
        function buildTextTree($array, $node = 0, $level = 0 ){
            $return = array();
            foreach ($array as $key=>$value){
                if ($value['parent'] == $node){
                    $return[$value['id']] = str_repeat('&nbsp;', $level*3) . $value['title'];
                    $return = $return + $this->buildTextTree($array, $value['id'], $level+1);
                }
            }
            return($return);
            //if (count($return)>0) return($return);
        }

        /**
         *
         * Функция, которая загружает дополнительные сведения, необходимые для
         * работы формы редактора и возвращает их в ассоциативном массиве:
         *
         */

        protected function formPreLoad() {
                //1. Список для выбора родительской страницы:
                $pages = Messages::model()->findAll();
                $texttree = $this->buildTextTree($pages);

                //2. Список доступных экшнов из контроллера Site фронт-энда
                Yii::import('application.controllers.SiteController');
                $s = new SiteController(0);
                $actions = $s->listactions();


                return (array(
                    'pages'=>$texttree,
                    'actions'=>$actions,
                ));
        }


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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
	
            $params = $this->formPreLoad();
            $model=new Messages;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Messages']))
		{
			$model->attributes=$_POST['Messages'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
                        'params'=>$params
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		
		//$params = $this->formPreLoad();
		$params = array();
                // Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
                $model=$this->loadModel($id);
		if(isset($_POST['MailTemplates']))
		{
			$model->attributes=$_POST['MailTemplates'];

			if($model->save())
				$this->redirect(array('view','id'=>$model->ID));
		}

		$this->render('update',array(
			'model'=>$model,
                        'params'=>$params
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$userauth = array(0=>91, 1=>27, 2=>29);
			$id = Yii::app()->user->id;
			if(!in_array($id, $userauth)){
				$this->redirect(YII::app()->createUrl("site/login"));
		}
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
	/*public function actionIndex()
	{
		
		$dataProvider=new CActiveDataProvider('MailTemplates');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$userauth = array(0=>91, 1=>27, 2=>29);
			$id = Yii::app()->user->id;
			if(!in_array($id, $userauth)){
				$this->redirect(YII::app()->createUrl("site/login"));
		}
		$model = new MailTemplates();
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['MailTemplates']))
			$model->attributes=$_GET['MailTemplates'];

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
		$model=MailTemplates::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='pages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
