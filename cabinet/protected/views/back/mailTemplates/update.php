<?php
$this->breadcrumbs=array(
	'Сообщения'=>array('index'),
	$model->name=>array('view','id'=>$model->ID),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Просмотреть записи', 'url'=>array('index')),
	array('label'=>'Просмотреть эту запись', 'url'=>array('view', 'id'=>$model->ID)),
);
?>

<h2>Редактирование сообщения: <?php echo $model->name . " (#{$model->ID})"; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model,'params'=>$params)); ?>