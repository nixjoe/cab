<?php
$this->breadcrumbs=array(
	'MessagesTemplates',
);

$this->menu=array(
	array('label'=>'Create MessagesTemplate', 'url'=>array('admin')),
);
?>

<h1>MessagesTemplates</h1>

<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
