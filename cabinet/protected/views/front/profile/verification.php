
<?/*
<script type="text/javascript" src="/js/jquery.jqUploader.js"></script>
*/?>
<script type="text/javascript" >
	$(document).ready(function() {
		/*
			$("#file").jqUploader({
				background:	"141517",
				barColor:	"C7C7C7"

			});
		*/
	
		$('#file').change(function(){
			$('#fi').val($(this).val());
			$('#file1').show();
			
		});
		$('#file1').change(function(){
			$('#fi1').val($(this).val());
		});
	});
</script>

<style type="text/css">
	#tkzd {
		font-size: 17px; cursor: pointer;

	}

	#tkzd:hover {
		color:white;
	}
</style>
<div class="cnt left">
	<h3><?= Yii::t('payment', 'Аттестация кошелька') ?></h3>
	<div class="separator"></div>
	<span style=" cursor: pointer;  font-size: 15px; color:white" id="tkzd" onclick="$('#ttt').toggle('fast');"><?= Yii::t('payment', 'Требования к загружаемым документам') ?></span><br>
	<div style="display: none;" id="ttt">
	<p><?= Yii::t('payment', 'Уважаемые клиенты, процедура аттестации кошелька необходима для подтверждения Вашей личности и личных данных, указанных Вами при регистрации в личном кабинете. Данная мера предусмотрена для обеспечения сохранности Ваших денежных средств при выводе с личного кошелька и торгового счета личного кабинета. Компания FxPrivate гарантирует, что присылаемые Вами документы ни в коем случае не будут использованы для иных целей, кроме аттестации личного кошелька.') ?></p>
<p><?= Yii::t('payment', 'К документам, предоставляемым Вами для аттестации кошелька, предъявляются следующие требования:') ?></p>
<ul style="list-style-type: decimal;">
	<li><?= Yii::t('payment', 'Для аттестации кошелька граждан стран СНГ необходимы цветные сканированные копии страницы национального паспорта (или аналогичного удостоверения личности) с фото и ФИО, а также, если имеется, страницы паспорта на русском языке (или оборотной стороны удостоверения личности);') ?></li>
<li><?= Yii::t('payment', 'Для аттестации кошелька граждан стран дальнего зарубежья необходима сканированная копия паспорта или ID-карты;') ?></li>
<li><?= Yii::t('payment', 'При невозможности предоставления сканированной копии, допускается аттестация по сфотографированным документам. В таком случае, фотография должна быть четкой и крупной. Сфотографирован должен быть весь разворот (страница документа), а не его отдельная часть;') ?></li>
<li><?= Yii::t('payment', 'Сканированные копии обязательно должны быть цветными и достаточно хорошего качества, чтобы возможно было прочесть информацию на них. Черно-белые сканированные копии документов не допускаются к аттестации;') ?></li>
<li><?= Yii::t('payment', 'В целях ускорения аттестации, убедительная просьба разворачивать отсканированные копии таким образом, чтобы их можно было легко и быстро прочесть. Заранее спасибо;') ?></li>
<li><?= Yii::t('payment', 'Не допускается загрузка поддельных документов;') ?></li>
<li><?= Yii::t('payment', 'Объем загружаемых файлов не должен превышать 2 Мб;') ?></li>
<li><?= Yii::t('payment', 'Загружаемые файлы могут быть только в форматах gif, jpeg, jpg, png;') ?></li>
<li><?= Yii::t('payment', 'При подаче документов на аттестацию, пожалуйста, обязательно впишите в предложенную форму реквизиты документов (прежде всего, номер и/или серию документа). В случае незаполнения указанных полей заявка на аттестацию будет отклонена.') ?></li>
</ul>
</div>

	<?php echo CHtml::form('','post',array('enctype'=>'multipart/form-data')); ?>
		<div class="form">
		 <div class="row">
            <p class="label-left"/>
            <div class="full-center">
                <?php $this->widget('Flashes'); ?>
            </div>
        </div>
		<div class="row">
			<label class="label-left" for="doc_name"><?= Yii::t('payment', 'Название документа') ?>: </label><br>
			<input id="doc_name" style="width: 50%" class="input-full" type="text" maxlength="32" value="<?=(isset($_POST['UsersDocs']['docname'])?$_POST['UsersDocs']['docname']:null)?>" name="UsersDocs[docname]" autocomplete="off">
		</div>
		<div class="row">
			<label class="label-left" for="doc_serial"><?= Yii::t('payment', 'Серия и номер документа') ?>: </label><br>
			<input id="doc_serial" style="width: 50%" class="input-full" type="text" maxlength="32"  value="<?=(isset($_POST['UsersDocs']['docnumber'])?$_POST['UsersDocs']['docnumber']:null)?>" name="UsersDocs[docnumber]" autocomplete="off">
		</div>
		<div class="row">
			<label class="label-left" for="doc_who"><?= Yii::t('payment', 'Кем выдан документ') ?>: </label><br>
			<input id="doc_who" style="width: 50%" class="input-full" type="text" maxlength="100"  value="<?=(isset($_POST['UsersDocs']['docissuer'])?$_POST['UsersDocs']['docissuer']:null)?>"  name="UsersDocs[docissuer]" autocomplete="off">
		</div>
		<?/*
		<div class="row" style="position: relative;">
			<input type="submit" class="inp-file" onclick="$('#file').trigger('click');return false;" value="Выберите файл">
			<input id="fi" style="width: 31%; padding-left: 140px;" class="input-full" type="text" maxlength="32" readonly="true" autocomplete="off">
		</div>
		<div class="row" style="position: relative;">
			<input type="submit" class="inp-file" onclick="$('#file1').trigger('click');return false;" value="Выберите файл">
			<input id="fi1" style="width: 31%; padding-left: 140px;" class="input-full" type="text" maxlength="32" readonly="true" autocomplete="off">
		</div>
		*/?>
		<div class="row" style="position: relative;">
		<?php echo CHtml::activeFileField($model, 'scan', array('style' => 'border: 1px solid black; display: block;', 'id' => 'file')); ?>
		</div>
		<div class="row" style="position: relative;">
		<?php echo CHtml::activeFileField($model, 'scan1', array('style' => 'border: 1px solid black; display: none;', 'id' => 'file1')); ?>
		</div>
		<div class="captcha row">
			 <?$this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
			<label for="UsersDocs_verifyCode" class="label-captcha label-left"><?= Yii::t('payment', 'Введите код на картинке') ?></label>
			<input type="text" id="UsersDocs_verifyCode" name="UsersDocs[verifyCode]" autocomplete="off" class="captcha-input input-full">
		</div>
		</div>
	


</br>
	<?=CHtml::submitButton( Yii::t('payout', 'Отправить'), array('class' => 'greenbutton'));?>

	<?php echo CHtml::endForm(); ?>

</div>