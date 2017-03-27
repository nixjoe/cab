<?php
$this->breadcrumbs=array(
	'Футер',
);
?>

<h1>Футер</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'id'=>'footers_list',
)); ?>

<div class="form-actions">
    <a class="btn btn-primary btn-small" href="<?php echo $this->createUrl('footer/create') ?>">Добавить</a>
</div>
