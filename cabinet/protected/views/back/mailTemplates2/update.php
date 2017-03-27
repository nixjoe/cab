<?php
$this->breadcrumbs=array(
	'Сообщения'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Просмотреть записи', 'url'=>array('index')),
	array('label'=>'Просмотреть эту запись', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h2>Редактирование сообщения: <?php echo $model->title . " (#{$model->id})"; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model,'params'=>$params)); ?>