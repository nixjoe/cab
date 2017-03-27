<?php
$this->breadcrumbs=array(
	'Msg Messages'=>array('index'),
	$model->ID=>array('view','id'=>$model->ID),
	'Update',
);

$this->menu=array(
	array('label'=>'List MsgMessages','url'=>array('index')),
	array('label'=>'Create MsgMessages','url'=>array('create')),
	array('label'=>'View MsgMessages','url'=>array('view','id'=>$model->ID)),

);
?>

<h1>Update MsgMessages <?php echo $model->ID; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>