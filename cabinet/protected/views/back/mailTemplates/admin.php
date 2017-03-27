<?php
$this->breadcrumbs=array(
	'Сообщение'
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
		array(
            'name'=>'subject',
            'htmlOptions'=>array('style'=>'width:100px;'),
        ),
        array(
            'name'=>'name',
            'htmlOptions'=>array('style'=>'width:50px;'),
        ),
		array(
            'name'=>'template',
            'type'=>'html',
            'htmlOptions'=>array('style'=>'width:450px;'),
        ),
        array(
            'name'=>'language',
            'htmlOptions'=>array('style'=>'width:50px;'),
        ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<style>
    #pages-grid img{ max-width: 450px; }
    #pages-grid table.items { table-layout: auto };
</style>