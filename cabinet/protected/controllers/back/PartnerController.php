<?php

class PartnerController extends Controller
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
				'actions'=>array('index', 'saveAgreement'),
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
		if (isset($_POST['remove'])){
		    foreach($_POST['remove'] as $id=>$val){
				$this->deletelangconstant($id);
				$sql = "DELETE FROM `partnerinfo` WHERE  `id` = '".$id."' ";				
				$connection->createCommand($sql)->execute();
			}	
		}
		if(isset($_POST['save'])) {
			
			if (!empty($_POST['partner'])){
				foreach($_POST['partner'] as $id=>$val){
					$sql = "UPDATE `partnerinfo` SET `value` = '".$val."', `lnurl` = '".(isset($_POST['lnuri'][$id]) ? $_POST['lnuri'][$id] : null)."', `order` = '".$_POST['order'][$id]."'   WHERE `id` = '".$id."' ";				
					$connection->createCommand($sql)->execute();
				}	
			}
			if (!empty($_POST['lang'])){
				$sql = "INSERT INTO `partnerinfo` SET  `key` = 'lang', `lnurl` = '".(isset($_POST['lang']) ? $_POST['lang'] : null)."', `value` = '".$_POST['lnurl']."', `order` = '".$_POST['orderlang']."' ";				
				$connection->createCommand($sql)->execute();
			}
			if (!empty($_POST['url'])){
				$sql = "INSERT INTO `partnerinfo` SET  `key` = 'url',  `lnurl` = '".(isset($_POST['razdel']) ? $_POST['razdel']  : null)."', `value` = '".$_POST['url']."', `order` = '".$_POST['orderurl']."' ";				
				$connection->createCommand($sql)->execute();
				$this->addlangconstant($_POST['razdel']);
			}
			
			if (!empty($_POST['translation'])){
				foreach($_POST['translation'] as $id1=>$val1){
					$sql = "UPDATE `sourcemessage` SET `message` = '".$val1."'  WHERE `id` = '".$id1."' ";				
					$connection->createCommand($sql)->execute();
				}	
			}
		}
		
		/*$sql= "SELECT * FROM `partnerinfo` ORDER BY `key`, `value`";				
		$data=$connection->createCommand($sql)->queryAll();
		$this->render('index',array(
				'dataProvider'=>$data,
				
			));
		*/
		$criteria=array(
            'order'=>'`key`, `order`'
		);
		$dataProvider=new CActiveDataProvider('PartnerLinks', array(
			'criteria'=>$criteria
		));

        $languages = Languages::model()->findAll(array('order'=>'sort asc', 'condition'=>'active = 1'));
        $agreementModel = new stdClass();
        $agreementModel->agreement = array();

        $agreementMsg = LngMessages::model()->with('lngTranslate_')->findByAttributes(array('category'=>'partner', 'message'=>'AGREEMENT_LINK'));
        $agreementLng = array();
        if ($agreementMsg && $agreementMsg->lngTranslate_) {
            foreach($agreementMsg->lngTranslate_ as $tr)
            $agreementLng[$tr->language] = $tr->translation;
        }

        foreach($languages as $lang) {
            $agreementModel->agreement[$lang->iso] = isset($agreementLng[$lang->iso]) ? $agreementLng[$lang->iso] : '';
        }
		
		$this->render('index',array(
				'dataProvider'=> $dataProvider,
                'languages' => $languages,
                'agreementModel' => $agreementModel,
			));

	}
	function deletelangconstant($id){
		$connection=Yii::app()->db;
		$sql = 'SELECT `lnurl` FROM `partnerinfo` WHERE `id` = \''.$id.'\'';
		$name = $connection->createCommand($sql)->queryRow();
		if (isset($name['lnurl']) && $name['lnurl']){
			$sql = 'DELETE  FROM `sourcemessage` WHERE `message` = \''.$name['lnurl'].'\' AND `category` = "partner"';
			$connection->createCommand($sql)->execute();
		}
	}
	function addlangconstant($name, $newname = false){
			$connection=Yii::app()->db;
			$sql = 'SELECT `id` FROM `sourcemessage` WHERE `message` = \''.$name.'\' AND `category` = "partner"';
			$id = $connection->createCommand($sql)->queryRow();
			if (isset($id) && isset($id['id']) && $id['id']){
				//$sql = 'UPDATE  `sourcemessage` SET  `message` = \''.$name.'\' WHERE id = \''.$id['id'].'\'';
				//$connection->createCommand($sql)->execute();
				return $id['id'];
			}else{
				$sql = 'INSERT INTO  `sourcemessage` SET  `message` = \''.$name.'\', `category` = "partner" ';
				$connection->createCommand($sql)->execute();
				return false;
			}
	}
	function get_partner_translations($ids){
		$connection=Yii::app()->db;
		 $sql= "SELECT * FROM `sourcemessage` WHERE `category` = 'partner' AND id IN (".$ids.") ORDER BY `id`";				
		$data=$connection->createCommand($sql)->queryAll();
		if (!empty($data)){
			foreach ($data as $item){
				echo '<input style="width:100%;" type="text" name="translation['.$item['id'].']" value="'.htmlspecialchars($item['message']).'" /><br />';
			}
		}
		
	}

    public function actionSaveAgreement() {
        $userauth = array(0=>91, 1=>27, 2=>29);
        $id = Yii::app()->user->id;
        if(!in_array($id, $userauth)){
            $this->redirect(YII::app()->createUrl("site/login"));
        }

        if (isset($_POST['agreement'])) {
            $agreementMsg = LngMessages::model()->with('lngTranslate_')->findByAttributes(array('category'=>'partner', 'message'=>'AGREEMENT_LINK'));
            $newMsg = false;
            if (!$agreementMsg) {
                $agreementMsg = new LngMessages();
                $agreementMsg->category = 'partner';
                $agreementMsg->message = 'AGREEMENT_LINK';
                $newMsg = true;
            }
            $translations = array();
            if ($agreementMsg && $agreementMsg->lngTranslate_) {
                foreach($agreementMsg->lngTranslate_ as $tr)
                    $translations[$tr->language] = $tr;
            }

            $trans = Yii::app()->db->beginTransaction();
            try {
                if ($newMsg) {
                    $agreementMsg->save();
                }
                foreach($_POST['agreement'] as $lang=>$link) {
                    if (!isset($translations[$lang])) {
                        $tr = new LngTranslations();
                        $tr->id = $agreementMsg->id;
                        $tr->language = $lang;
                        $translations[$lang] = $tr;
                    }
                    $translations[$lang]->translation = $link;
                    $translations[$lang]->save();
                }
                $trans->commit();
            } catch (Exception $e) {
                $trans->rollback();
                throw $e;
            }
        }

        $this->redirect(array('index'));
    }
}
