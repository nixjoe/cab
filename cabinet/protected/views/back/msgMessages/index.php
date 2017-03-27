<?php
$this->breadcrumbs=array(
	'Msg Messages',
);

$this->menu=array(
	array('label'=>'Create MsgMessages','url'=>array('create')),
	//array('label'=>'Manage MsgMessages','url'=>array('admin')),
);
?>

<h1>Msg Messages</h1>



	<label>№ сообщения</label>
	<input type="text" name="index" value="" />
	<a href="#" onclick="window.location = '/chang_area_private_cab.php?r=msgMessages/view&id='+$(this).prev().val()">Поиск</a>


<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	
)); ?>
