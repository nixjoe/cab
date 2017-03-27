<?php

class LanguagesController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','viewCategory'),
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

		$model = new Languages;
		
		if(isset($_POST['save'])) {
        	$lngModel = new Languages();
        	$langs = $lngModel->findAll("`active` = '1' AND `iso` != 'ru'");
			
			foreach($langs as $lng) {
				if($lng->iso == 'ua') $lng->iso = 'uk'; elseif($lng->iso == 'cn') $lng->iso = 'zh_cn';				
				$l = LngTranslations::model()->find('id=:id AND language=:language', array(':id'=>$id, ':language' =>$lng->iso));
				
				if($l) {
					$sql = "UPDATE message SET translation = '".$_POST['tr'][$lng->iso]."' WHERE  id = $id AND language = '$lng->iso'";
					$connection->createCommand($sql)->execute();
				} elseif(isset($_POST['tr'][$lng->iso])) {
					$sql = "INSERT INTO message(`id`, `language`, `translation`) VALUES ('".$id."','".$lng->iso."','".$_POST['tr'][$lng->iso]."')";
					$connection->createCommand($sql)->execute();
				}
			}			
		}		
				$translations = LngTranslations::model()->findAll('id=:id', array(':id'=>$id));
		
		
		$tmpArr=null;
		if($translations)
		foreach($translations as $tr) {
			$tmpArr[$tr['language']] = $tr['translation'];	
		}
		
//var_dump($res);		
		
		$translations = $tmpArr;


		
		$this->render('view',array(
			'model'=>$model,
			'id'=>$id,
			'msg'=>$res['message'],
			'translation'=>$translations,
			//'managers' => $managers
		));
	}
	
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionViewCategory($cat)
	{
		$userauth = array(0=>91, 1=>27, 2=>29);
			$id = Yii::app()->user->id;
			if(!in_array($id, $userauth)){
				$this->redirect(YII::app()->createUrl("site/login"));
		}		
$connection=Yii::app()->db;
		//var_dump($sql);		
				$dataProvider=new CActiveDataProvider('LngMessages', array(
			        'criteria'=>array(
			        		'condition'=>"category = '$cat'",
			            'order'=>'category DESC',
			        ),			
				));	

        // если запрос асинхронный, то нам нужно отдать только данные
        if(Yii::app()->request->isAjaxRequest && isset($_POST['id'])){
        	$lngModel = new Languages();
        	$langs = $lngModel->findAll("`active` = '1' AND `iso` != 'ru'");
        	
			$id = $_POST['id'];		
//			echo var_dump($_POST);
			foreach($langs as $lng) {
				if($lng->iso == 'ua') $lng->iso = 'uk'; elseif($lng->iso == 'cn') $lng->iso = 'zh_cn';
				$l = LngTranslations::model()->find('id=:id AND language=:language', array(':id'=>$id, ':language' =>$lng->iso));
				
				if($l) {
					$sql = "UPDATE message SET translation = '".$_POST['tr'][$lng->iso]."' WHERE  id = $id AND language = '$lng->iso'";
					$connection->createCommand($sql)->execute();
				} elseif(isset($_POST['tr'][$lng->iso])) {
					$sql = "INSERT INTO message(`id`, `language`, `translation`) VALUES ('".$id."','".$lng->iso."','".$_POST['tr'][$lng->iso]."')";
					$connection->createCommand($sql)->execute();
				}
			}	
			
            Yii::app()->end();
        }
        else {
			$this->render('index',array(
				'dataProvider'=>$dataProvider,
				
			));
		}
	}	

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new LngMessages;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['MsgMessages']))
		{
			$model->attributes=$_POST['MsgMessages'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
				$this->redirect(array('view','id'=>$model->id));
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
		if(isset($_POST['save'])) {
						
			foreach($_POST['langs'] as $id => $lang) {
				if(isset($lang['active']))$lang['active'] = 1;
				else $lang['active'] = 0;
				$mLang = Languages::model()->findByPK($id);
				$mLang->attributes = $lang;
				$mLang->save();
			} 
		}
		
		if(isset($_POST['add'])) {
			if(isset($lang['active']))$lang['active'] = 1;
			else $lang['active'] = 0;			
			$mLang = new Languages();
			$mLang->attributes = $_POST['lang'];
			$mLang->save();
		}
		
		if(isset($_POST['del'])) {
			 Languages::model()->deletebyPK($_POST['lid']);
		}
		$dataProvider=new CActiveDataProvider('Languages');
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
