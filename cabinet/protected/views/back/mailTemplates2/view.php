<?php
$this->breadcrumbs=array(
	'Сообщения'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'Просмотреть записи', 'url'=>array('index')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->id)),
	/*array('label'=>'Удалить запись', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы уверены?')),*/
);
?>

<h2>Просмотр сообщения: <?php echo $model->title; ?></h2>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'mail',
		'title',
		'name',
		'full_text:html',
	),
)); ?>
