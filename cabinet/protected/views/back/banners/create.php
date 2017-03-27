<?php
$this->breadcrumbs=array(
	'Баннеры'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список баннеров', 'url'=>array('admin')),
);
?>

<h1>Создать баннер</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>