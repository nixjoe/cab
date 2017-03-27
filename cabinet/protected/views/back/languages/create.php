<?php
$this->breadcrumbs=array(
	'Msg Messages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List MsgMessages','url'=>array('index')),
	array('label'=>'Manage MsgMessages','url'=>array('admin')),
);
?>

<h1>Create MsgMessages</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>