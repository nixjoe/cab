<?php
$this->breadcrumbs=array(
	'Вывод средств'
);


$this->menu=array(
	array('label'=>'Добавить запись', 'url'=>array('create')),
);


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


<?/* echo CHtml::link('Расширенный поиск','#',array('class'=>'search-button')); */?>
<div class="search-form" style="display:none">
<?/* $this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'pages-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
