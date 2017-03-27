<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.mousewheel.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.datepicker.min.js", CClientScript::POS_END);
 
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/dtp.css", '');      
    Yii::app()->clientScript->registerScript('inend_register',"
jQuery(function($){
        $.datepicker.regional['ru'] = {
                closeText: '".Yii::t('calendar', 'Закрыть')."',
                prevText: '&#x3c;". Yii::t('calendar', 'Пред') ."',
                nextText: '". Yii::t('calendar', 'След') ."&#x3e;',
                currentText: '". Yii::t('calendar', 'Сегодня') ."',
                monthNames: ['". Yii::t('calendar', 'Январь') ."','". Yii::t('calendar', 'Февраль') ."','". Yii::t('calendar', 'Март') ."','". Yii::t('calendar', 'Апрель') ."','". Yii::t('calendar', 'Май') ."','". Yii::t('calendar', 'Июнь') ."',
                '". Yii::t('calendar', 'Июль') ."','". Yii::t('calendar', 'Август') ."','". Yii::t('calendar', 'Сентябрь') ."','". Yii::t('calendar', 'Октябрь') ."','". Yii::t('calendar', 'Ноябрь') ."','". Yii::t('calendar', 'Декабрь') ."'],
                monthNamesShort: ['". Yii::t('calendar', 'Янв') ."','". Yii::t('calendar', 'Фев') ."','". Yii::t('calendar', 'Мар') ."','". Yii::t('calendar', 'Апр') ."','". Yii::t('calendar', 'Май') ."','". Yii::t('calendar', 'Июн') ."',
                '". Yii::t('calendar', 'Июл') ."','". Yii::t('calendar', 'Авг') ."','". Yii::t('calendar', 'Сен') ."','". Yii::t('calendar', 'Окт') ."','". Yii::t('calendar', 'Ноя') ."','". Yii::t('calendar', 'Дек') ."'],
                dayNames: ['". Yii::t('calendar', 'воскресенье') ."','". Yii::t('calendar', 'понедельник') ."','". Yii::t('calendar', 'вторник') ."','". Yii::t('calendar', 'среда') ."','". Yii::t('calendar', 'четверг') ."','". Yii::t('calendar', 'пятница') ."','". Yii::t('calendar', 'суббота') ."'],
                dayNamesShort: ['". Yii::t('calendar', 'вск') ."','". Yii::t('calendar', 'пнд') ."','". Yii::t('calendar', 'втр') ."','". Yii::t('calendar', 'срд') ."','". Yii::t('calendar', 'чтв') ."','". Yii::t('calendar', 'птн') ."','". Yii::t('calendar', 'сбт') ."'],
                dayNamesMin: ['". Yii::t('calendar', 'Вс') ."','". Yii::t('calendar', 'Пн') ."','". Yii::t('calendar', 'Вт') ."','". Yii::t('calendar', 'Ср') ."','". Yii::t('calendar', 'Чт') ."','". Yii::t('calendar', 'Пт') ."','". Yii::t('calendar', 'Сб') ."'],
                weekHeader: '". Yii::t('calendar', 'Не') ."',
                dateFormat: 'dd.mm.yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,
                yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['ru']);
});    
	$(document).ready(function() {
		$('#file').change(function(){
			$('#fi').val($(this).val());	
		});	
		
		$.datepicker.setDefaults($.datepicker.regional['ru']);

		$('#payment_date').datepicker();
	});
    $('.select-container .radiobox :radio[name=\"Notify[method]\"]').live('change',function(){
			if($(this).val() == 0 || $(this).val() == 2) {
				$('#oth_sys').show('fast');
			} else {
				$('#oth_sys').hide('fast');				
			}
			if($(this).val() == 1) {
				$('#pay_by_bank').show('fast');
			} else {
				$('#pay_by_bank').hide('fast');				
			}
    });	
",  CClientScript::POS_END);
    ?>
<style type="text/css">
	#tkzd {
		font-size: 17px; cursor: pointer;

	}
	
	#tkzd:hover {
		color:white;		
	}
</style>

<div class="cnt left">
	<h3><?= Yii::t('account', 'Уведомить о платеже') ?></h3>
	<?php $this->widget('Flashes'); ?>
	<p class="separator"> </p>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Notify-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>  	
	<div class="form">
		<div class="row">
			<label class="label-left" for="payment_no"><?= Yii::t('account', 'Номер торгового счета или FxPrivate-кошелька') ?></label><br>
			<?/*<input id="payment_no" class="input-full" type="text" maxlength="32" name="notify[no]" autocomplete="off">	*/?>
			<div id="select-notify" style="width: 192px; " class="select-container rel-container midleft-input">
				<div class="valuebox">
					<p></p>
				</div>
				<div class="radiobox">
						<div class="optcontainer">
							<input id="ytnotify" type="hidden" name="Notify[val]" value="">
							<span id="notify">
							
								<?$str='';
								if (sizeof($transits)>0)
									$i=0; foreach ($transits as $key=>$val) { $i++ ;
										
											$str.= ' <input id="notify_'.$i.'" class="" type="radio" name="Notify[no]" value="FX-'.$val->currency_['alphaCode'].'">
												 <label for="notify_'.$i.'">FX-'.$val->currency_['alphaCode']. Yii::t('account', 'Транзитный счет') .'</label>
												';
									}
									foreach ($tradeacc as $key=>$val) { $i++ ;
									?>
									<?
										$str.= ' <input id="notify_'.$i.'" class="" type="radio" name="Notify[no]" value="'.$val['mtID'].'">
												 <label for="notify_'.$i.'">'.$val['mtID'].' '.$val->fxType_['name'].'</label>
												';
										

									?>
									<?}	echo $str;?>
													
							</span>
						</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<label class="label-left" for="payment_sum"><?= Yii::t('account', 'Сумма платежа') ?></label>
			<label class="label"  style="margin-left: 80px;" for="val"><?= Yii::t('account', 'Валюта платежа') ?></label>
			<br>
			<input id="payment_sum" class="input-full" type="text" maxlength="32" name="Notify[sum]" autocomplete="off">	
			<div id="select-val" style="width: 80px; margin-left:18px;" class="select-container rel-container midleft-input">
				<div class="valuebox" >
					<p>USD</p>
				</div>
				<div class="radiobox">
						<div class="optcontainer">
						<input id="ytval_method" type="hidden" name="Notify[val]" value="">
							<span id="val_method">
							<? foreach ($transits as $key=>$val): ?>
								<input id="val_<?= $key ?>" class="" type="radio" name="Notify[val]" value="<?= $val->currency_['alphaCode'] ?>">
								<label for="val_<?= $key ?>"><?= $val->currency_['alphaCode'] ?></label>
							<? endforeach; ?>													
							</span>
						</div>
				</div>
			</div>
		</div>
		<div class="row">
			<label class="label-left" for="payment_date"><?= Yii::t('account', 'Дата платежа') ?></label><br>
			<input id="payment_date" class="input-full" type="text" maxlength="32" name="Notify[date]" autocomplete="off">	
		</div>
		<div class="row">
			<label class="label"  style="margin-bottom: 15px;" for="payment_method"><?= Yii::t('account', 'Способ платежа') ?></label><br>
			<div id="select-method" style="width: 192px; " class="select-container rel-container midleft-input">
				<div class="valuebox" style="margin-top:0px;">
					<p><?= Yii::t('account', 'Способ оплаты') ?></p>
				</div>
				<div class="radiobox">
						<div class="optcontainer">
						<input id="ytPayment_method" type="hidden" name="Notify[method]" value="">
							<span id="payment_method">
								<input id="payment_method_0" class="" type="radio" name="Notify[method]" value="0">
								<label for="payment_method_0"><?= Yii::t('account', 'Электронные деньги') ?></label>
								<input id="payment_method_1" class="" type="radio" name="Notify[method]" value="1">
								<label for="payment_method_1"><?= Yii::t('account', 'Банковский перевод') ?></label>
								<input id="payment_method_2" class="" type="radio" name="Notify[method]" value="2">
								<label for="payment_method_2"><?= Yii::t('account', 'Другие способы оплаты') ?></label>																
							</span>
						</div>
				</div>
			</div>
		</div>
		
		<div class="row" style="display: none;" id="oth_sys">
			<label class="label-left" style="width: 350px;" for="payment_name"><?= Yii::t('account', 'Впишите, пожалуйста, название платежной системы и номер кошелька, с которого был осуществлен платеж') ?></label><br>
			<input id="payment_name" class="input-full" style="width: 300px" type="text" maxlength="64" name="Notify[name]" autocomplete="off">	
		</div>
		
		<div class="row" style="display: none;" id="pay_by_bank">
			<label class="label-left" for="payment_name"><?= Yii::t('account', 'Для ускорения процедуры зачисления средств на Ваш счет, пожалуйста, прикрепите скан-копию банковской квитанции о платеже') ?></label><br>
			<div class="row" style="position: relative;">
				<input type="submit" class="inp-file" onclick="$('#file').trigger('click');return false;" value="<?= Yii::t('account', 'Выберите файл') ?>">
				<input id="fi" style="width: 31%; padding-left: 140px;" class="input-full" type="text" maxlength="32" readonly="true" autocomplete="off">	
			<?php echo CHtml::activeFileField($model, 'scan', array('style' => 'display: none;', 'id' => 'file')); ?>				
			</div>	
		</div>	

		<div class="captcha row">
		<?$this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
		<?=CHtml::activeLabel($model, 'verifyCode',array("class"=>"label-captcha label-left"))?>
		<?=$form->textField($model, 'verifyCode',array("class"=>"captcha-input input-full","autocomplete"=>"off"))?>
		<?php echo $form->error($model,'verifyCode',array('class'=>'line-error')); ?>
		</div>
			<div class="full-center">
            <div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('account', 'Сообщить о платеже')); ?>
			</div>
			</div>	
	</div>
<?php $this->endWidget(); ?>
</div>