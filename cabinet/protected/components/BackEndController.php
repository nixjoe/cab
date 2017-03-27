<?php
class BackEndController extends Controller
{

    // лейаут
//    public $layout = 'application';
        
    // меню
    public $menu = array();
    
    // крошки
    public $breadcrumbs = array('123');
    
    
    /*
        Фильтры
    */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }
    
    
    /*
        Права доступа
    */
    public function accessRules()
    {
        return array(
            // даем доступ только админам
            array(
                'allow',
                //'roles'=>array('admin'),
                'users'=>array('admin')
            ),
                                    // всем остальным разрешаем посмотреть только на страницу авторизации
            array(
                'allow',
        'actions'=>array('/site/login/'),
        'users'=>array('*'),
      ),
            // запрещаем все остальное
            array(
                'deny',
                'users'=>array('*'),
            ), 
        );		
    }
}