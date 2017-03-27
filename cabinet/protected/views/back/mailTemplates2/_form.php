<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pages-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'mail'); ?>
		<?php echo $form->textField($model,'mail',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'mail'); ?>
	</div>

	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	

 	<div class="row">
		<?php echo $form->labelEx($model,'full_text'); ?>
		<?
                $this->widget('application.extensions.wysiwyg.CKkceditor',array(
                    "model"=>$model,                # Data-Model
                    "attribute"=>'full_text',         # Attribute in the Data-Model
                    "height"=>'400px',
                    "width"=>'100%',
                    'filespath'=>dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . "media",
                    "filesurl"=>Yii::app()->baseUrl . "/media",
                    )
                );
                ?>
		<?php echo $form->error($model,'full_text'); ?>
	</div>



<?php if (false) {?>
	<div class="row">
		<?php echo $form->labelEx($model,'full_text'); ?>
		<?php echo $form->textArea($model,'full_text',array('rows'=>30, 'cols'=>50)); ?>
		<?php echo $form->error($model,'full_text'); ?>
	</div>
<?php }?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->