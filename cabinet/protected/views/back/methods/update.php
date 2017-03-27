<?php
$this->breadcrumbs=array(
	'Вывод средств'=>array('index'),
	$model->name=>array('view','id'=>$model->ID),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Просмотреть записи', 'url'=>array('index')),
	array('label'=>'Просмотреть эту запись', 'url'=>array('view', 'id'=>$model->ID)),
	array('label'=>'Удалить запись', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Вы уверены?')),
	array('label'=>'Добавить запись', 'url'=>array('create')),
);
?>

<h2>Редактирование средств: <?php echo $model->name . " (#{$model->ID})"; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model,'params'=>$params)); ?>