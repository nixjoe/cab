<?php
$this->breadcrumbs=array(
	'MultiLanguages'=>array('index'),
	$id,
);
?>

<h1>Редактирование переводов для текста #<?php echo $id; ?></h1>
<b><?= htmlspecialchars($msg) ?></b>
<div class="row buttons">
<form method='POST' action=''>
	<legend>
		<label for="en">Английский</label>	
		<textarea name='tr[en]' id='en'><?= isset($translation["en"])?$translation["en"]:null ?></textarea>
	</legend>
	<legend>
		<label for="zh">Китайский</label>	
		<textarea name='tr[zh]' id='zh'><?= isset($translation["zh"])?$translation["zh"]:null ?></textarea>
	</legend>	
	<legend>
		<label for="es">Испанский</label>	
		<textarea name='tr[es]' id='es'><?= isset($translation["es"])?$translation["es"]:null ?></textarea>
	</legend>
	<legend>
		<label for="id">Индонезийский</label>	
		<textarea name='tr[id]' id='id'><?= isset($translation["id"])?$translation["id"]:null ?></textarea>
	</legend>
	<legend>
		<label for="my">Малайзийский</label>	
		<textarea name='tr[my]' id='my'><?= isset($translation["my"])?$translation["my"]:null ?></textarea>
	</legend>
	<legend>
		<label for="uk">Украинский</label>	
		<textarea name='tr[uk]' id='uk'><?= isset($translation["uk"])?$translation["uk"]:null ?></textarea>
	</legend>			
	<legend>
		<label for="ar">Арабский</label>	
		<textarea name='tr[ar]' id='ar'><?= isset($translation["ar"])?$translation["ar"]:null ?></textarea>
	</legend>		
	<input type="submit" name="save" value="Сохранить">
</form>
	</div>
	
<?php
	$cs=Yii::app()->getClientScript();
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.fancybox.js', CClientScript::POS_END);
?>	

<script type="text/javascript">

jQuery(document).ready(function(){
			$('a#a_user_msg').fancybox({
				'hideOnContentClick': true,
'autoDimensions': false,
'width' : 600
			});
	});
</script>
