<?php
$this->breadcrumbs=array(
	'Баннеры',
);

$this->menu=array(
	array('label'=>'Создать баннер', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('banners-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Баннеры</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'banners-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
        array(
            'type' => 'raw',
            'value' => 'CHtml::image($data->content, "", array("style"=>"max-width:150px; max-height:200px"))'
        ),
		'url',
		'language',
        array(
            'name'=>'status',
            'value'=>'Banners::statusTitle($data->status)',
            'filter'=>Banners::statusList()
        ),
        array(
            'name' => 'position',
            'class' => 'ext.OrderColumn.OrderColumn',
        ),
		array(
			'class'=>'CButtonColumn',
            'template' => '{update}{delete}'
		),
	),
)); ?>
