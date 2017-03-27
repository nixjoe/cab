<?php
if(isset($_GET['cat']))
	$this->breadcrumbs=array(
		'Languages'=>array('index'),
		$_GET['cat']
	);
else 
	$this->breadcrumbs=array(
		'Languages',
	);


?>
<script type="text/javascript" >
	function lDel(id) {
		$('#lang_id').val(id);
		$('#del_form').submit();
	}
</script>
<form style='display: none;' id='del_form' method="POST" action="">
	<input type='hidden' name="lid" value='' id='lang_id'>
	<input type='hidden' name='del' value="true">
</form>
<form action="" method="POST">
<div class="view" style="cursor:pointer" onclick="$(this).next('div.dnone').toggle()">
<table>
	<thead>
		<td></td>
		<td><?php echo CHtml::encode(Languages::model()->getAttributeLabel('name')); ?></td>
		<td><?php echo CHtml::encode(Languages::model()->getAttributeLabel('title')); ?></td>
		<td><?php echo CHtml::encode(Languages::model()->getAttributeLabel('iso')); ?></td>
		<td><?php echo CHtml::encode(Languages::model()->getAttributeLabel('sort')); ?></td>		
		<td><?php echo CHtml::encode(Languages::model()->getAttributeLabel('active')); ?></td>				
	</thead>
	<tbody>
		<td></td>
		<td><input type='text' name='lang[name]' value=''></td>
		<td><input type='text' name='lang[title]' value=''></td>
		<td><input type='text' name='lang[iso]' style='width: 30px' value=''></td>
		<td><input type='text' name='lang[sort]' style='width: 30px' value=''></td>		
		<td><input type='checkbox' name='lang[active]'></td>	
	</tbody>
</table>
</div>

<input type="submit" name="add" value="Добавить">
</form>
<form action="" method="POST">
<?php

 $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)) 
?>

<input type="submit" name="save" value="Сохранить">
</form>