<?php
$this->breadcrumbs=array(
	'Footers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Footer', 'url'=>array('index')),
	array('label'=>'Create Footer', 'url'=>array('create')),
	array('label'=>'Update Footer', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Footer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Footer', 'url'=>array('admin')),
);
?>

<h1>View Footer #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'top_links',
		'pay_systems',
		'menu',
		'soc_buttons',
		'language',
		'publish',
	),
)); ?>
