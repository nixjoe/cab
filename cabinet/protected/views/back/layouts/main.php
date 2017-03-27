
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/backcss/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/backcss/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.fancybox.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/backcss/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/backcss/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');
$cs=Yii::app()->getClientScript();
//WYSIWYG for all textareas:
$cs->registerCssFile($cs->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');
$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/redactor/redactor.js');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/js/redactor/css/redactor.css');
//$cs->registerScript('textarea',"jQuery(document).ready(function(){jQuery('textarea').redactor();});");
//Time picker for all text fields with class .time:
/*
$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/timepicker/jquery.timePicker.min.js');
$cs->registerCssFile(Yii::app()->request->baseUrl . '/js/timepicker/timePicker.css');
$cs->registerScript('.time',"jQuery(document).ready(function(){jQuery('.time').timePicker();});");
//Для тех случаев, когда мы не можем разбить поле дата и время на два - у нас
//будет два дополнительных поля, при изменении которых мы будем изменять основное.
$cs->registerScript('.datetimeupdate',"jQuery(document).ready(function(){
    datetime = $('.datetime').val();
    if (typeof(datetime)!='undefined'){
        datetime = datetime.split(' ');
        $('.dateupdate').val(datetime[0]);
        $('.timeupdate').val(datetime[1]);
    }
    jQuery('.datetimeupdate').live('blur change',function(){
        $('.datetime').val($('.dateupdate').val() + ' ' + $('.timeupdate').val())
    })
    ;});");
*/
        ?>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php //echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->

	<div id="mainmenu">

		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
                array('label'=>'Главная', 'url'=>array('/'),'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Пользователи', 'url'=>array('/users'),'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Сообщения пользователей', 'url'=>array('/msgMessages'),'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Переводы', 'url'=>array('/lngMessages'),'visible'=>!Yii::app()->user->isGuest),		
				array('label'=>'Языки', 'url'=>array('/languages'),'visible'=>!Yii::app()->user->isGuest),								
				array('label'=>'Ссылки', 'url'=>array('/links'),'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Настройка писем', 'url'=>array('/mailTemplates'),'visible'=>!Yii::app()->user->isGuest),

				array('label'=>'Сбособы вывода средств', 'url'=>array('/methods'),'visible'=>!Yii::app()->user->isGuest),
				array('label'=>'Раздел партнёр', 'url'=>array('/partner'),'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Футер', 'url'=>array('/footer'),'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Настройки', 'url'=>array('/settings'),'visible'=>!Yii::app()->user->isGuest),
                array('label'=>'Баннеры', 'url'=>array('/banners'),'visible'=>!Yii::app()->user->isGuest),

				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),

			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
	        &copy; <?php echo date('Y'); ?> Fx-private<br/>
		All Rights Reserved.<br/>
		Powered by &#9733
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
