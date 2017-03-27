<?php
$this->breadcrumbs=array(
	'Messages Templates'=>array('index'),
	'Manage',
);
$this->menu=array(
	array('label'=>'List MsgMessages','url'=>array('index')),
	array('label'=>'Create MsgMessages','url'=>array('create')),
);
/*
$this->menu=array(
	array('label'=>'Добавить запись', 'url'=>array('create')),
);
*/

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('pages-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<h1>Manage Msg Messages</h1>

<?/* echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); */?>
<div class="search-form" style="display:none">
<?/* $this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'msg-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'mail',
		'subject',
		'template:html',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
