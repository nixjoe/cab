<?php

class UsersController extends Controller
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


	public function actionAddletter(){
		if (intVal($_POST['id'])){

			$connection=Yii::app()->db;
			$sql = 'INSERT INTO `msg_threads`  SET
						`type` = \'1\' ,
						`status` = \'1\' ,
						`client` = \''.$_POST['id'].'\',
						`title` = \'Аттестация личных данных\',
						`assignee` = \'23\'
						 ';
			$connection->createCommand($sql)->execute();
			$sql = 'SELECT LAST_INSERT_ID() as id FROM `msg_threads` ';
			$id = $connection->createCommand($sql)->queryRow();
			$sql = 'INSERT INTO `msg_messages`  SET
						`datetime` = \''.date('Y-m-d H:i:s', time()).'\',
						`sender` = \'23\' ,
						`text` = \'Вам отказано в аттестации личных данных. '.($_POST['text']).'\',
						`status` = \'1\',
						`thread_id` = \''.$id['id'].'\'
						 ';
			$connection->createCommand($sql)->execute();

			echo json_encode(array('success' => true));
		}else{
			echo json_encode(array('success' => false));
		}

		exit();
	}


	public function actionViewImg($hash, $filename) {
	    $connection=Yii::app()->db;

	    $model = new DataBank();

	    $doc = $model->find('hash=:hash AND filename=:filename',array(
		':hash'=>$hash, ':filename' => $filename
	    ));
		//echo $sql = 'SELECT * FROM `databank` WHERE `hash` = \''.$hash.'\'  AND filename= \''.$filename.'\'';
		//$doc=$connection->createCommand($sql)->queryRow();
	    $this->render('image', array('filetype'=>$doc['filetype'], 'value'=>$doc['value']));
	}

	public function actionLoadImg($hash, $filename) {
	    $connection=Yii::app()->db;

	    $model = new DataBank();

	    $doc = $model->find('hash=:hash AND filename=:filename',array(
		':hash'=>$hash, ':filename' => $filename
	    ));

	    $this->render('imageL', array('filetype'=>$doc['filetype'], 'value'=>$doc['value'], 'filename' => $doc['filename'], 'filesize' => $doc['filesize']));
	}

	public function actionView($id)
	{
		
		$connection=Yii::app()->db;
		$sql = "SELECT * FROM `users_docs` WHERE `userID` = $id";
	    $userdoc=$connection->createCommand($sql)->queryRow();

		if (is_array($userdoc)){
			$sql = 'SELECT * FROM `databank` WHERE `hash` = \''.base64_encode(pack('H*', sha1($id . $userdoc['docname']))).'\' ';
			$doc=$connection->createCommand($sql)->queryRow();
		}


		$transits = Transitaccounts::model()->
                        with(
                            array('currency_',)
                        )->
                        findAll(
                                array('condition'=>'userID='.$id)
                        );
		$tradeaccounts = Tradeaccounts::model()->with('fxType_')->findAll(
                    array('condition'=>'userID='.$id)
                    );
				foreach ($tradeaccounts as $key=>$val) {
                    $request[] = intval($val['mtID']);
                }
				$mtdata = array();
                $mt = new MTconnector();
                if($mt->connected())
                    {
                    if (!empty ($request)) {
                        $mt4accounts = $mt->find($request);
                        foreach ($mt4accounts as $key=>$val){
                            $mtdata[$val['login']]['leverage'] = $val['leverage'];
                            $mtdata[$val['login']]['balance'] = $val['balance'];
/*                              TODO: Добавить сохранение данных в кеш!!!
                            Tradeaccounts::model()-> */
                        }
                    }

                }
		$connection=Yii::app()->db;
		$sql = "SELECT COUNT(*) as cnt FROM msg_messages WHERE  status = 0	AND sender = $id";
		$countmess=$connection->createCommand($sql)->queryRow();
		
		$this->render('view',array(
			'model'=>$this->loadModel($id),
			'transitid'=>$this->loadModel($id)->transitID,
             'transits'=>$transits,
			 'tradeacc'=>$tradeaccounts,
			 'mtdata'=>$mtdata,
			 'activate' => isset($doc['value']) ? 1 : 0,
			 'img' => isset($doc['value']) ? $doc['value'] : 0,
			'messcount' => $countmess['cnt']
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		
            $params = $this->formPreLoad();
            $model=new Users;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
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
		$connection=Yii::app()->db;

		if(isset($_POST['act']) && $_POST['act'] == 'np') {
			$sql = 'UPDATE `users` SET `password` = \''.base64_encode(pack('H*', sha1($_POST['newpass']))).'\' WHERE `ID` = \''.$id.'\'';
			$connection->createCommand($sql)->execute();
			return $sql;
		}	
		
		if(isset($_POST['Users'])){
				
				if($_POST['Users']['regdate']){
					$_POST['Users']['regdate'] = strtotime($_POST['Users']['regdate']);
				}
                if($_POST['Users']['birthDate']){
                    $_POST['Users']['birthDate'] = date('Y-m-d', strtotime($_POST['Users']['birthDate']));
                }
				$model->attributes=$_POST['Users'];

				$model->save();
				$this->redirect(array('view','id'=>$model->ID));
		}	
		
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'getLeaverages') {
		    $fxType = $_REQUEST['fxtype'];

		    $fx_model = Fxtypes::model()->findByPk($fxType);

		    $response['leaverages'] = explode(',',$fx_model->leverage);

		    die(json_encode($response));
		}
		
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'checkVerify') {
 			$connection=Yii::app()->db;			
			$response['result'] = 0;
			
			$cid = $_REQUEST['id'];
			
			$sql = "SELECT * FROM `payoutcredentials` WHERE `ID` = '".$cid."'";
			$cred = $connection->createCommand($sql)->queryRow();
			
			if($cred) {
				$sql = "SELECT u.`transitID` FROM `payoutcredentials` p LEFT JOIN `users` u ON u.`ID` = p.`userID` WHERE p.`status` = '1' AND p.`accountnumber` = '".$cred['accountnumber']."' AND p.`payoutmethodID` = '".$cred['payoutmethodID']."' AND p.`ID` <> '".$cid."'";
				$at_cred = $connection->createCommand($sql)->queryAll();

				if($at_cred) {
					$response['result'] = 1;
									
					foreach($at_cred as $val) {
						$tmp_arr[] = $val['transitID'];
					}
					$response['purse'] = implode(', ', $tmp_arr);
					unset($tmp_arr);
				}
				
			}			
			
			die(json_encode($response));
		}

		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'setStatus') {
		    $transferID = $_REQUEST['transferID'];
		    $val = $_REQUEST['status'];
            $db = Yii::app()->db->beginTransaction();
            try {
                $md = Transfers::model()->findByPK($transferID);
                $oldStatus = $md->status;
                $md->status = $val;
                if ($md->type == 9 && $val == '0' && $oldStatus != $val) {
                    $transitAcc = Transitaccounts::model()->with('user_')->find(array(
                        'condition'=>"currency='840' AND user_.transitID='{$md->sourceID}'"
                    ));
                    $mt = new MTconnector();
                    if($transitAcc && $mt->connected()) {
                        $mt->transaction(
                            $md->targetID,
                            $md->amount,
                            "Din-{$md->amount}:{$md->sourceID}>{$md->targetID}"
                        );
                        $mt->transaction(
                            $md->sourceID,
                            -$md->amount,
                            "Win-{$md->amount}:<{$md->sourceID}"
                        );

                        $time = new CDbExpression('now()');
                        $log = new Transfers();
                        $partner = Tradeaccounts::model()->with('user_')->find('mtID=\''.$md->targetID.'\'');
                        $log->attributes = array(
                            'issuer' => $partner->user_->ID,
                            'date' => $time,
                            'amount' => $md->amount,
                            'actualamount' => $md->amount,
                            'comission' => 0,
                            'currency' => 840,
                            'currencyN' => 840,
                            'sourceID' => $md->sourceID,
                            'targetID' => $md->targetID,
                            'status' => 0,
                            'type' => 10,
                        );
                        $log->save();

                        $transitAcc->amount -= $md->amount;
                        $transitAcc->save();
                    }
                }
                $md->save();
                $db->commit();
                if (in_array($val, array('0', '2'))){
                    $lang =  Languages::model()->findByAttributes(array('active' => 1, 'id' => $model->language),array('order'=>'iso'));
                    $mail_params = array(
                        'firstName' => $model->givenName,
                        'middleName' => $model->middleName,
                        'lastName' => $model->familyName,
                        'language' => $lang ? $lang->iso : 'en',
                    );
                    Mail::send('cashing', $mail_params, $model->email);
                }
            } catch (Exception $e) {
                $db->rollback();
                echo $e;
            }
            return;
		}
		
		if(isset($_POST['verify'])) {
			
		    $connection=Yii::app()->db;
		    foreach($_POST['verify'] as $vv) {
				if($vv == 'fix' or  $vv == 'nofix') $val = $vv;
		    }
			if (isset($val)){
				switch($val) {
				case 'fix':
					if($model->status != 1) {

                        $sql = 'INSERT INTO `msg_threads`  SET
                                    `type` = \'1\' ,
                                    `status` = \'1\' ,
                                    `client` = \''.$id.'\',
                                    `title` = \'Аттестация личных данных\',
                                    `assignee` = \''.Yii::app()->user->ID.'\'
                                     ';
                        $connection->createCommand($sql)->execute();
                        $sql = 'SELECT LAST_INSERT_ID() as id FROM `msg_threads` ';
                        $th_id = $connection->createCommand($sql)->queryRow();

                        $sql = 'INSERT INTO `msg_messages`  SET
                                    `datetime` = \''.date('Y-m-d H:i', time()).'\',
                                    `sender` = \''.Yii::app()->user->ID.'\' ,
                                    `text` = \'Аттестация личных данных прошла успешно\',
                                    `status` = \'1\',
                                    `thread_id` = \''.$th_id['id'].'\'
                                     ';
                        $connection->createCommand($sql)->execute();

                        $model->status = 1;

                        $lang =  Languages::model()->findByAttributes(array('active' => 1, 'id' => $model->language),array('order'=>'iso'));
                        $mail_params = array(
                            'firstName' => $model->givenName,
                            'middleName' => $model->middleName,
                            'lastName' => $model->familyName,
                            'language' => $lang ? $lang->iso : 'en',
                        );
                        Mail::send('verify_success', $mail_params, $model->email);
					}
					break;
				case 'nofix':
					if($model->status != 0) {
					$sql = 'INSERT INTO `msg_threads`  SET
								`type` = \'1\' ,
								`status` = \'1\' ,
								`client` = \''.$id.'\',
								`title` = \'Аттестация личных данных\',
								`assignee` = \''.Yii::app()->user->ID.'\'
								 ';
					$connection->createCommand($sql)->execute();
					$sql = 'SELECT LAST_INSERT_ID() as id FROM `msg_threads` ';
					$th_id = $connection->createCommand($sql)->queryRow();
					$sql = 'INSERT INTO `msg_messages`  SET
								`datetime` = \''.date('Y-m-d H:i', time()).'\',
								`sender` = \'23\',
								`text` = \''.$_POST['reason'].'\',
								`status` = \'1\',
								`thread_id` = \''.$th_id['id'].'\'
								 ';
					$connection->createCommand($sql)->execute();

					$model->status = 0;
                        $lang =  Languages::model()->findByAttributes(array('active' => 1, 'id' => $model->language),array('order'=>'iso'));
                        $mail_params = array(
                            'firstName' => $model->givenName,
                            'middleName' => $model->middleName,
                            'lastName' => $model->familyName,
                            'language' => $lang ? $lang->iso : 'en',
                            'reason'   => $_POST['reason']
                        );
                        Mail::send('verify_deny', $mail_params, $model->email);
					}
					break;
				}
			}
		}

		if(isset($_POST['send_msg'])) {
		    $from = $_POST['send_msg']['from'];
		    $subject = $_POST['send_msg']['subject'];
		    $msg = $_POST['send_msg']['msg'];
		    $sql = 'INSERT INTO `msg_threads`  SET
						`type` = \'1\' ,
						`status` = \'1\' ,
						`client` = \''.$id.'\',
						`title` = \''.$subject.'\',
						`assignee` = \''.Yii::app()->user->ID.'\'
						 ';
		    $connection->createCommand($sql)->execute();
		    $sql = 'SELECT LAST_INSERT_ID() as id FROM `msg_threads` ';
		    $th_id = $connection->createCommand($sql)->queryRow();
		    $sql = 'INSERT INTO `msg_messages`  SET
						`datetime` = \''.date('Y-m-d H:i', time()).'\',
						`sender` = \''.$from.'\' ,
						`text` = \''.$msg.'\',
						`status` = \'1\',
						`thread_id` = \''.$th_id['id'].'\'
						 ';
		    $connection->createCommand($sql)->execute();
		}
		if(isset($_POST['pm']))
		{
			foreach ($_POST['pm'] as $key => $items){
				$sql = "UPDATE  payoutcredentials SET `status` = ".$items."  WHERE id = ".$key." ";
				$connection->createCommand($sql)->execute();
			}
		}
		
		if(isset($_POST['trans_minus'])) {
		    $Tid = $_POST['trans_minus']['id'];
		    $sum = $_POST['trans_minus']['sum'];
		    $tr = Transitaccounts::model()->findByPK($Tid);
		    $tr->amount -= $sum;
		    $tr->save();
		}

		if(isset($_POST['trans_plus'])) {
		    $Tid = $_POST['trans_plus']['id'];
		    $sum = $_POST['trans_plus']['sum'];
		    $tr = Transitaccounts::model()->findByPK($Tid);
		    $tr->amount += $sum;
		    $tr->save();
		}
		$mt = new MTconnector();
		if(isset($_POST['trade_minus'])) {

		    $Tid = $_POST['trade_minus']['id'];
		    $sum = $_POST['trade_minus']['sum'];
		    if($mt->connected()) {
			$mt->transaction(
                        $Tid,
                        -$sum,
                        "Win-{$sum}:<{$Tid}"
                        );
		    }
		}

		if(isset($_POST['trade_plus'])) {
		    $Tid = $_POST['trade_plus']['id'];
		    $sum = $_POST['trade_plus']['sum'];
		    if($mt->connected()) {
			$mt->transaction(
                        $Tid,
                        $sum,
                        "Din-{$sum}:>{$Tid}"
                        );
		    }
		}
		if(isset($_POST['docs']))
		{
			foreach ($_POST['docs'] as $key => $items){
				switch ($items['status']) {
					case 0:
					   $status = 2;
						break;
					case 1:
					   $status = 1;
						break;
					case 2:
						$status = 0;
						break;
					default:
						exit();
						$status = 0;
				}
				  $sql = 'UPDATE  `users` SET `status` = \''.$status.'\' WHERE `ID` = \''.$id.'\'  ' ;
					
				 $connection->createCommand($sql)->execute();
				 $sql = 'UPDATE  `users_docs` SET `docname` = \''.$items['docname'].'\',
												 `docissuer` = \''.$items['docissuer'].'\',
												 `docnumber` = \''.$items['docnumber'].'\',
												 `status` = \''.$items['status'].'\'
						WHERE `userID` = \''.$id.'\' AND  id = \''.$key.'\' ' ;
					
				$connection->createCommand($sql)->execute();
				
			}
			
		}
		
		//Способы вывода и Номер счета
		
		$sql = "SELECT p.*, pm.name as pmname FROM payoutcredentials p
				LEFT JOIN payoutmethods pm ON  pm.id = p.payoutmethodID
				WHERE p.userID = '".$id."' ";
		$payoutcredentials=$connection->createCommand($sql)->queryAll();

		
		$sql = "SELECT * FROM `users_docs` WHERE `userID` = $id";
		$userdoc=$connection->createCommand($sql)->queryAll();

		if (is_array($userdoc)){
		    foreach($userdoc as &$userdoc_item) {
				$sql = 'SELECT * FROM `databank` WHERE `hash` = \''.base64_encode(pack('H*', sha1($id . $userdoc_item['docname']))).'\' AND `filename`  <> ""';
				$doc=$connection->createCommand($sql)->queryAll();
				$userdoc_item['doc'] = $doc;
		    }
		}

	
		$managers = UsersManagers::model()->findAll();
		$fxtypes = Fxtypes::model()->findAll();
		$transits = Transitaccounts::model()->
                        with(
                            array('currency_',)
                        )->
                        findAll(
                                array('condition'=>'userID='.$id)
                        );
		$tradeaccounts = Tradeaccounts::model()->with('fxType_')->findAll(
                    array('condition'=>'userID='.$id)
                    );
				foreach ($tradeaccounts as $key=>$val) {
                    $request[] = intval($val['mtID']);
                }
				$mtdata = array();

		$payouts = Transfers::model()->findAll('issuer=:issuer AND type IN (\'6\', \'9\') ORDER BY date DESC ', array(':issuer' => $id,));
        if($mt->connected())
            {
            if (!empty ($request)) {
                $mt4accounts = $mt->find($request);
                foreach ($mt4accounts as $key=>$val){
                    $mtdata[$val['login']]['leverage'] = $val['leverage'];
                    $mtdata[$val['login']]['balance'] = $val['balance'];
                    /*
                    TODO: Добавить сохранение данных в кеш!!!
                    Tradeaccounts::model()->
                    */
                }
            }

        }
		$connection=Yii::app()->db;
		$sql = "SELECT COUNT(*) as cnt FROM msg_messages WHERE  status = 0	AND sender = $id";
		$countmess=$connection->createCommand($sql)->queryRow();
		
		$this->render('update',array(
			'model'=>$model,
			'userdoc'=>$userdoc,
			'transitid'=>$this->loadModel($id)->transitID,
			'params'=>$params,
			'fxtypes'=>$fxtypes,
			'transits'=>$transits,
			'tradeacc'=>$tradeaccounts,
			'payouts'=>$payouts,
			'mtdata'=>$mtdata,
			'managers'=>$managers,
			'payoutcredentials' => $payoutcredentials,
			'messcount' => $countmess['cnt']
			
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

			$this->loadModel($id)->deleteByPk($id);

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

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
		$connection=Yii::app()->db;
		
		if (isset($_POST['message'])){
			if (isset($_POST['users'])){
				foreach($_POST['users'] as $items){
					$sql = 'INSERT INTO `msg_threads`  SET
									`type` = \'1\' ,
									`status` = \'1\' ,
									`client` = \''.$items.'\',
									`title` = \''.$_POST['theme'].'\',
									`assignee` = \'23\'
									 ';
						$connection->createCommand($sql)->execute();
						$sql = 'SELECT LAST_INSERT_ID() as id FROM `msg_threads` ';
						$th_id = $connection->createCommand($sql)->queryRow();
						$sql = 'INSERT INTO `msg_messages`  SET
									`datetime` = \''.date('Y-m-d H:i', time()).'\',
									`sender` = \'23\',
									`text` = \''.$_POST['message'].'\',
									`status` = \'1\',
									`thread_id` = \''.$th_id['id'].'\'
									 ';
						
						$connection->createCommand($sql)->execute();
				}
			}
			
		}
		
		if (isset($_GET['ajaxing'])){
			$sql = "SELECT id, middleName, familyName, givenName  FROM users WHERE country = ".$_GET['country']." ORDER BY familyName ";
			$users=$connection->createCommand($sql)->queryAll();
			$str = '';
			foreach ($users as $items){
				$str .="<option value='".$items['id']."'>".$items['familyName']."  ".$items['givenName']." ".$items['middleName']." </option>";
			}
			echo $str;
			exit();
		}
		
		$sql = "SELECT id, middleName, familyName, givenName  FROM users ORDER BY familyName";
		$users=$connection->createCommand($sql)->queryAll();
		$sql = "SELECT isoID, rus  FROM countries ORDER BY rus";
		$countries=$connection->createCommand($sql)->queryAll();
		
		$model = new Users();
		$model->unsetAttributes();  // clear any default values

		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
			'users'=>$users,
			'countries'=>$countries,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Users::model()->with('country_')->findByPk($id);
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
