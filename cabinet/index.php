<?php
mb_internal_encoding('UTF-8');

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/front.php';
//$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 5);

require_once(dirname(__FILE__) . '/protected/globals.php');
require_once($yii);
//Yii::createWebApplication($config)->run();
Yii::createWebApplication($config)->runEnd('front');
