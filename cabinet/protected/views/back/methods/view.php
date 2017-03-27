<?php
$this->breadcrumbs=array(
	'Вывод средств'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Просмотреть записи', 'url'=>array('index')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->ID)),
	array('label'=>'Удалить запись', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Вы уверены?')),
	array('label'=>'Добавить запись', 'url'=>array('create')),

);
?>

<h2>Просмотр средств: <?php echo $model->name . " (#{$model->ID})"; ?></h2>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'name',
		
	),
)); ?>
