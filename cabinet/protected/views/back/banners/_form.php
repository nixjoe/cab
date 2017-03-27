

<div class="form form-horizontal">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'banners-form',
	'enableAjaxValidation'=>false,
));

function kcfinder_select($c, $model, $attribute, array $options=array()) {
    return $c->widget('application.extensions.wysiwyg.KCFinder', array_merge(
        array(
            'model' => $model,
            'attribute' => $attribute,
            'filespath'=>dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . 'media',
            "filesurl"=>Yii::app()->baseUrl . '/media',
        ), $options
    ));
}

?>

	<p class="note">Поля <span class="required">*</span> обьязательны.</p>
    <p> &nbsp; </p>

	<?php echo $form->errorSummary($model); ?>

    <div class="control-group">
        <?php echo $form->labelEx($model,'language', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'language', CHtml::listData(Languages::model()->findAll(), 'iso', 'name'), array('class'=>'input-block-level')); ?>
            <?php echo $form->error($model,'language'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model,'url', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'url',array('size'=>60,'maxlength'=>255, 'class'=>'input-block-level')); ?>
            <?php echo $form->error($model,'url'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model,'content', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php kcfinder_select($this, $model, 'content'); ?>
            <?php echo $form->error($model,'content'); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->labelEx($model,'status', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'status', Banners::statusList(), array('class'=>'input-block-level')); ?>
            <?php echo $form->error($model,'status'); ?>
        </div>
    </div>

	<div class="row buttons form-actions">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', array('class'=>'btn btn-primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->