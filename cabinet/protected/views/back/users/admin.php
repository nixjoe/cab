<?php
$this->breadcrumbs=array(
	'Пользователи'
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
		'familyName',
		'givenName',
		'middleName',
         array(       
            'name'=>'regdate',
            'value'=>'date("d/m/y", $data->regdate)',
        ),

		'email',
		'phone',
		
		
		
		array(
      'class'=>'myButtonColumn',
      'deleteButtonVisible'=>'$data->ID=0',
		),
	),
)); ?>

<h3>Отправить сообщение пользователям</h3>
<div class="form">
<form method="post" action="/chang_area_private_cab.php?r=users">
<?if(isset($countries)){?>
<div class="row">
	<select style="width:400px" id="country" name="country">
			<?foreach ($countries as $items){?>
				<option value="<?=$items['isoID']?>"><?=$items['rus']?></option>
			<?}?>
	</select>
<?}?>
</div>
<?if(isset($users)){?>
<div class="row">
	<select style="width:400px; height:400px;"  id="users" multiple="multiple" name="users[]">
			<?foreach ($users as $items){?>
				<option value="<?=$items['id']?>"><?=$items['familyName']?> <?=$items['givenName']?> <?=$items['middleName']?>  </option>
			<?}?>
	</select>
<?}?>
</div>
<div class="row">
	<label>Тема сообщения</label>
	<textarea name="theme" style="width: 500px;"></textarea>
</div>
<div class="row">
	<label>Сообщение</label>
	<textarea name="message" style="width: 500px; height: 300px;"></textarea>
</div>
<div class="row">
	<input type="submit" value="Отправить" />
</div>
</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('select#country').change(function(){
			$('#users').load('/chang_area_private_cab.php?r=users&country='+this.value+'&ajaxing=true');
		});
	});
</script>