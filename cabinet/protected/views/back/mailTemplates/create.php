<?php

Yii::app()->clientScript->registerScript('ready',"
    $('#Pages_title').live('keyup',function(){
        $('#Pages_menuName').val($('#Pages_title').val());
        })
",  CClientScript::POS_BEGIN);

$this->breadcrumbs=array(
	'Pages'=>array('index'),
	'Создание записи',
);

$this->menu=array(
	array('label'=>'Просмотреть страницы', 'url'=>array('index')),
);
?>


<?php echo $this->renderPartial('_form', array('model'=>$model,'params'=>$params)); ?>