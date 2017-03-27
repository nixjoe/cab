<?php
mb_internal_encoding('UTF-8');

// путь до фреймворка и нужного нам конфига
$yii = dirname(__FILE__).'/../framework/yii.php';
$config = dirname(__FILE__).'/protected/config/back.php';
 
// включать дебаг?
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);


require_once(dirname(__FILE__).'/protected/globals.php');
// подключаем фреймворк
require_once($yii);

$admin_side = true;

Yii::createWebApplication($config)->runEnd('back');

