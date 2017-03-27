<?php
$data = array();
$ajaxdata = array();
$hintData = array();
foreach ($payoutmethods as $k=>$v) {
    $data[$v->ID] = "<div id='s_$v->ID'>".Yii::t('payout', $v->name)."</div>";
    $ajaxdata["s_$v->ID"] = $v->papers_required;
    $hintData[$v->ID] = Yii::t('payoutD', $v->name);
}
$ajaxdata = json_encode($ajaxdata);
$hintData = json_encode($hintData);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.tooltip.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('onreadycreds',"
        var data = $ajaxdata;
        window._hintData = $hintData;
        window._hintTxt = null;
        if (data[$('input[name=\"Payoutcredentials[payoutmethodID]\"]:radio:checked+label div').attr('id')]==0)
            $('#Payoutcredentials_uploadPapers').attr('disabled', true);
        $('input[name=\"Payoutcredentials[payoutmethodID]\"]').change(function () {
            if (data['s_' + ($(this).attr('value'))] == 1 && $('#Payoutcredentials_uploadPapers').attr('disabled')) {
                $('#Payoutcredentials_uploadPapers').attr('disabled',false);
            }
            else if (data['s_' + ($(this).attr('value'))] == 0 && !$('#Payoutcredentials_uploadPapers').attr('disabled')) {
                $('#Payoutcredentials_uploadPapers').attr('disabled', true);
            }
            window._hintTxt = window._hintData[$(this).val()]||'';
            $('#paymethodHint').html(window._hintTxt);
            if (window._hintTxt != '') {
                $('#paymethodHintBtn').show();
            } else {
                $('#paymethodHintBtn').hide();
            }
        })
	$(document).ready(function() {
		$('#Payoutcredentials_uploadPapers').change(function(){
			$('#fi').val($(this).val());
		});


		$('.padtable label').click(function(){
			if ($(this).children().attr('id') == 's_1'){
				$('#bank').show();
			}else{
				$('#bank').hide();
			}
		});

        $('.field-info-btn').tooltip({
            bodyHandler: function() {
                return $(this).next('.field-info').html() || '<div></div>';
            }
        });
	});
    ", CClientScript::POS_READY);
?>
<div class="cnt left">
    <h3><?= Yii::t('payout', 'Добавление платежного реквизита') ?></h3>
    <div class="separator"></div>
	<?/*<span onclick="$('#ttt').toggle('fast');" id="tkzd" style=" cursor: pointer; font-size: 17px;">Требования к загружаемым документам</span>
	<br>
	<div id="ttt" style="display: none;">
	<p>Объем загружаемых файлов не должен превышать 2 Мб. <br /> Загружаемые файлы могут быть только в форматах gif, jpeg, jpg, png</p>
	</div>
*/?>
<div style="margin-top:10px;" class="form">
    <?php $this->widget('Flashes'); ?>
    <div class="half-width right-marged left">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'payoutcredentials-credentials-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>


    <div class="row">
    <div class="shadowed padtable half-width" rules="rows" bordercolor="blue" border="1" frame="void">
        <div class="headrow"><div class="form"><?=CHtml::activeLabel($model, 'payoutmethodID')?></div></div>
    <?php
    if (!empty($data)) {
        echo CHtml::activeRadioButtonList($model, 'payoutmethodID',$data, array(
        'template'=>'<div class="trow">{input}{label}</div>',
        'encode'=>false,
        'separator'=>''
        ));
    } else
    {
        echo Yii::t('payout', "Нет доступных способов вывода. Обратитесь к администратору.");
    }
?>
    </div>
</div>
	<div class="row"><br/>
		<?php echo $form->label($model,'accountnumber'); ?><br/>
		<?php echo $form->textField($model,'accountnumber',array('class'=>'input-full','autocomplete'=>'off')); ?>
        <span class="field-info-btn field-info-btn-right" id="paymethodHintBtn" style="display:none"></span>
        <div class="field-info" style="display:none">
            <div class="flash notice">
                <p class="close"></p>
                <div class="header">
                    <div id="paymethodHint"></div>
                    <br />
                    <br />
                </div>
            </div>
        </div>
	</div>
	<div style="display:none;" id="bank" class="row">
		<p><?= Yii::t('payout', 'Пожалуйста, прикрепите скан копию документа с реквизитами Вашего банковского счета – это может быть выписка из банка с реквизитами Вашего счета.') ?></p>
	<span style=" cursor: pointer;  font-size: 15px; color:white" id="tkzd" onclick="$('#ttt').toggle('fast');"><?= Yii::t('payout', 'Требования к загружаемым документам') ?></span><br>
	<div style="display: none;" id="ttt">
	<p><?= Yii::t('payout', 'Объем загружаемых файлов не должен превышать 2 Мб. <br /> Загружаемые файлы могут быть только в форматах gif, jpeg, jpg, png') ?></p>
	</div>
	
	
	<div class="row rel-container papers-container"><br/>
		<?php echo $form->label($model,'uploadPapers'); ?><br/>

			<input type="submit" class="inp-file" style="width: 45%;" onclick="$('#Payoutcredentials_uploadPapers').trigger('click');return false;" value="<?= Yii::t('payout', 'Выберите файл') ?>">
			<input id="fi" style="width: 50%; padding-left: 158px;" class="input-full" type="text" maxlength="32" readonly="true" autocomplete="off">
			<?php //echo CHtml::activeFileField($model, 'uploadPapers', array('style' => 'border: 1px solid black; display: none;', 'id' => 'file', 'autocomplete'=>'off', 'multiple' => "multiple",'class' => 'multi')); ?>
	</div>
<div style='display: none;'>	<?
	  $this->widget('CMultiFileUpload', array(
		 'model'=>$model,
		 'attribute'=>'uploadPapers',
		 'accept'=>'jpeg|jpg|gif|png',
		 
  ));
  	?></div>
	

	</div>

	<div class="captcha row">
		<?$this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
		<?=CHtml::activeLabel($model, 'verifyCode',array("class"=>"label-captcha label-left"))?>
		<?=$form->textField($model, 'verifyCode',array("class"=>"captcha-input input-full","autocomplete"=>"off"))?>
	</div>
</div>
    <div class="half-width left">

    </div>
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('payout', 'Отправить')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>