<?php 
	$this->breadcrumbs=array(
		'partner');
?>

<style>
.partnerblock * {
	display:inline-block;
}
</style>

<p><b></b></p>
    <?php echo $this->renderPartial('_form_agreement', array('languages'=>$languages, 'model'=>$agreementModel)); ?>
<hr />
<br /><br />

<form method="POST">
<p><b>Редактирование русских переводов</b></p>
<?=$this->get_partner_translations('430, 431, 432, 442, 433, 437, 468, 469, 470')?>
<b>Добавление языка</b>
<div class="partnerblock">
	<label>Ссылка</label>
	<input type="text" value="" name="lnurl"/>
	<label>Язык</label>
	<input type="text" value="" name="lang"/>
	<label>Сортировка</label>
	<input type="text" value="" name="orderlang" style="width:20px;"/>
</div>
<b>Добавление ссылки</b>
<div class="partnerblock">
	<label>Ссылка</label>
	<input type="text" value="" name="url"/>
	<label>Название раздела</label>
	<input type="text" value="" name="razdel"/>
	<label>Сортировка</label>
	<input type="text" value="" name="orderurl" style="width:20px;"/>
</div>
<?php
 $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)) 		
?>
<input type="submit" name="save" value="Сохранить">
</form>
