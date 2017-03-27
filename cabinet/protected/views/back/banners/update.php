<?php
$this->breadcrumbs=array(
	'Banners'=>array('index'),
	$model->id,
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список баннеров', 'url'=>array('index')),
	array('label'=>'Создать баннер', 'url'=>array('create')),
);
?>

<h1>Редактировать баннер <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>