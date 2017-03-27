<?php
$payoutdata = array();
foreach ($payoutmethods as $k=>$v) {
    $data[$v->ID] = "<div>$v->name</div>";
}

?>
<div class="cnt left">
    <h3><?= Yii::t('payout', 'Добавление платежного реквизита') ?></h3>
    <div class="separator"></div>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'payoutcredentials-credentials-form',
	'enableAjaxValidation'=>false,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),    
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>
	<?php echo $form->errorSummary($model); ?>
<?php if (false) {?>
	<div class="row">
		<?php echo $form->label($model,'userID'); ?>
		<?php echo $form->textField($model,'userID'); ?>
		<?php echo $form->error($model,'userID'); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'payoutmethodID'); ?>
		<?php echo $form->textField($model,'payoutmethodID'); ?>
		<?php echo $form->error($model,'payoutmethodID'); ?>
	</div>
<?php } ?>
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
		<?php echo $form->error($model,'accountnumber',array('class'=>'line-error')); ?>
	</div>

	<div class="row rel-container"><br/>
		<?php echo $form->label($model,'papers'); ?><br/>
		<?php echo $form->fileField($model,'papers', array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'papers'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('payout', 'Отправить')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>