<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'msg-messages-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->dropDownListRow($model,'category',CHtml::listData(LngMessages::model()->findAll(array('group'=>'category','distinct'=>true,'order' => 'category ASC')), 'category', 'category'), array('empty'=>'', 'class'=>'span3')); ?>
    <?php echo CHtml::activeTextField($model,'newCategory',array('class'=>'span3','maxlength'=>11)); ?>

	<?php echo $form->textAreaRow($model,'message',array('class'=>'span6')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
