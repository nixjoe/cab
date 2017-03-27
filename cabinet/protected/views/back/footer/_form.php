<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'footer-form',
	'enableAjaxValidation'=>false,
));

$filesPath = dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . 'media';
$filesUrl = Yii::app()->baseUrl . '/media';

function footer_CKEditor($c, $model, $attribute, array $options=array()) {
    return $c->widget('application.extensions.wysiwyg.CKkceditor', array_merge(
            array(
                'model' => $model,
                'attribute' => $attribute,
                'height' => '200px',
                'width'=>'100%',
                'filespath'=>dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . 'media',
                "filesurl"=>Yii::app()->baseUrl . '/media',
            ), $options
        ));
}

?>

	<!--p class="note">Fields with <span class="required">*</span> are required.</p-->

	<?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model,'language'); ?>
        <?php echo $form->dropDownList($model, 'language', CHtml::listData(Languages::model()->findAll(), 'iso', 'name')); ?>
        <?php echo $form->error($model,'language'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'publish'); ?>
        <?php echo $form->checkBox($model,'publish'); ?>
        <?php echo $form->error($model,'publish'); ?>
    </div>
    <hr />

	<div class="row">
		<?php echo $form->labelEx($model,'top_links'); ?>
        <?php footer_CKEditor($this, $model, 'top_links'); ?>
		<?php echo $form->error($model,'top_links'); ?>
	</div>
    <hr />

	<div class="row">
		<?php echo $form->labelEx($model,'pay_systems'); ?>
        <?php footer_CKEditor($this, $model, 'pay_systems'); ?>
		<?php echo $form->error($model,'pay_systems'); ?>
	</div>
    <hr />

	<div class="row">
		<?php echo $form->labelEx($model,'menu'); ?>
        <?php footer_CKEditor($this, $model, 'menu'); ?>
		<?php echo $form->error($model,'menu'); ?>
	</div>
    <hr />

	<div class="row">
		<?php echo $form->labelEx($model,'soc_buttons'); ?>
        <?php footer_CKEditor($this, $model, 'soc_buttons'); ?>
		<?php echo $form->error($model,'soc_buttons'); ?>
	</div>

	<div class="row form-actions">
		<?php echo CHtml::submitButton('Сохранить', array('class'=>'btn btn-primary btn-small')); ?>
        <?php echo CHtml::link('Отмена', array('footer/index'), array('class'=>'btn btn-link btn-small')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->