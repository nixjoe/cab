<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'msg-messages-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'datetime',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'sender',array('class'=>'span5','maxlength'=>11)); ?>

	<?php echo $form->textFieldRow($model,'thread_id',array('class'=>'span5','maxlength'=>20)); ?>

	<?php echo $form->textFieldRow($model,'text',array('class'=>'span5','maxlength'=>500)); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
