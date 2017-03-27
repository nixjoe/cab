<div class="view" style="cursor:pointer" onclick="$(this).next('div.dnone').toggle()">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?php echo CHtml::encode($data->category); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('message')); ?>:</b>
	<?php echo CHtml::encode($data->message); ?>
	<br />

</div>
<? 
		$connection=Yii::app()->db;
		$sql = "SELECT * FROM message WHERE  id = '".$data->id."'";
		$res = $connection->createCommand($sql)->queryAll();
		$translation= NULL;
		foreach($res as $row) {
			$translation[$row['language']]= $row['translation'];
		}
		
		$languages = new Languages();
		$langs = $languages->findAll(); //$languages->findAll("`iso` != 'ru'");
?>
<div class="dnone" style="display: none;" id="form_<?=$data->id?>">
<form method='POST' action=''>
<? foreach($langs as $lang) : if($lang['iso'] == 'ua') { $lang['iso'] = 'uk'; } elseif($lang['iso'] == 'cn') { $lang['iso'] = 'zh_cn'; } ?>
	<legend>
		<label for="<?= $lang['iso'] ?>"><?= $lang['name'] ?></label>	
		<textarea style="width:100%;" name='tr[<?= $lang['iso'] ?>]' id='<?= $lang['iso'] ?>'><?= isset($translation[$lang['iso']])? html_entity_decode($translation[$lang['iso']]):null ?></textarea>
	</legend>
<? endforeach; ?>
	<input type="hidden" name="id" value="<?=$data->id?>">
<? 
echo CHtml::ajaxSubmitButton('Сохранить', '', array(
    'type' => 'POST',
),
array(
    // Меняем тип элемента на submit, чтобы у пользователей
    // с отключенным JavaScript всё было хорошо.
    'success'=>'js: function(data) {
                        alert("Сохранение прошло успешно");
                    }',
    'type' => 'submit'
));

?>	
</form>
</div>
