<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pages-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'subject'); ?>
		<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>150)); ?>
		<?php echo $form->error($model,'subject'); ?>
	</div>

	

 	<div class="row">
		<?php echo $form->labelEx($model,'template'); ?>
		<?
                $this->widget('application.extensions.wysiwyg.CKkceditor',array(
                    "model"=>$model,                # Data-Model
                    "attribute"=>'template',         # Attribute in the Data-Model
                    "height"=>'400px',
                    "width"=>'100%',
                    'filespath'=>dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . "media",
                    "filesurl"=>Yii::app()->baseUrl . "/media",
                    )
                );
                ?>
		<?php echo $form->error($model,'template'); ?>
	</div>



<?php if (false) {?>
	<div class="row">
		<?php echo $form->labelEx($model,'template'); ?>
		<?php echo $form->textArea($model,'template',array('rows'=>30, 'cols'=>50)); ?>
		<?php echo $form->error($model,'template'); ?>
	</div>
<?php }?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->