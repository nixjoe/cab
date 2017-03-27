<?php

class LinksController extends Controller
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
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
		$connection=Yii::app()->db;

		if(isset($_POST['save'])) {
		
			foreach($_POST['tr'] as $slug=>$lang_arr) 
				foreach($lang_arr as $key=>$val) {
					$sql = "UPDATE `ml_links` SET `url` = '".$val."' WHERE `language` = '".$key."' AND `slug` = '".$slug."'";				
					$connection->createCommand($sql)->execute();
				}
		}
		
		$dataProvider=new CActiveDataProvider('Links');
		
		$this->render('index',array(
				'dataProvider'=>$dataProvider,
				
			));

	}
}
