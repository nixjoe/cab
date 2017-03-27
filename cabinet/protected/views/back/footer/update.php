<?php
$this->breadcrumbs=array(
	'Футер'=>array('index'),
	'Редактировать',
);
?>

<h1>Редактировать Футер (<?php echo $this->getLangTitle($model->language), ', id: ', $model->id; ?>)</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>