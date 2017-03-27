<?php

class SettingsController extends Controller {

    public $layout='//layouts/column2';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function accessRules() {
        return array(
            array('allow',
                //'users'=>array('@'),
                'expression'=>array('SettingsController', 'checkAdminAccess')
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public static function checkAdminAccess($user) {
        return in_array($user->id, array(91, 27, 29));
    }

    public function actionIndex() {
        $file = dirname(__FILE__).'../../../config/settings.php';
        $model = new SettingsForm();
        $model->load($file);
        if (isset($_POST['SettingsForm'])) {
            if (!isset($_POST['SettingsForm']['bd_countries'])) {
                $_POST['SettingsForm']['bd_countries'] = array();
            }
            $model->setAttributes($_POST['SettingsForm']);
            $model->save();
            $this->redirect(array('index'));
        }
        $this->render('edit', array('model' => $model));
    }
} 