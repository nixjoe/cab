<?php 

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.autosize-min.js", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('create1',"
    $('textarea').autosize();
", CClientscript::POS_READY);

?>
<div class="cnt left">
    <h3 class="inline"><?= Yii::t('messages', 'Новое сообщение') ?></h3>
    <p class="separator"></p>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'msg-messages-index-form',
	'enableClientValidation'=>true,
)); ?>


<?php $this->widget('Flashes'); ?>
	<div class="row newmessage">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title'); ?>
	</div>
<?php if (false) {?>
        <div class="row newmessage">
		<?php echo $form->label($model,'assignee'); ?>
		<?php echo $form->dropDownList($model,'assignee',
                        CHtml::listData($managers, 'userID', 'position_name_'.$_GET['language'])); ?>
	</div>
<?php } ?>
    <div class="row newmessage">
        <?php echo $form->label($model,'assignee'); ?>        
        <div class="rel-container select-container">
            <div class="valuebox"><p></p>
            </div>
            <div class="radiobox">
                <div class="optcontainer">
                <?=$form->radioButtonList($model,'assignee',CHtml::listData($managers, 'userID', 'position_name_'.$_GET['language']) ,array(
                    'class'=>'',
                    'separator'=>''
                    ))?>
		    
		    
                </div>
            </div>
        </div>
    </div>
    
	<div class="row rel-container newmessagetext">
		<?php echo $form->textArea($model,'text',array('class'=>'overlayed')); ?>
                <?php echo $form->label($model,'text', array('class'=>'label-overlay')); ?>
	</div>
    <p class="separator"></p>
	<div class="row buttons newmessagebuttons">
		<?php echo CHtml::submitButton(Yii::t('messages', 'Отправить')); ?>     
	</div>
                <?php $this->endWidget(); ?>
    <div class="row buttons newmessagebuttons">
        <?=CHtml::beginForm(Yii::app()->createUrl('/messages'))?>
        <?=CHtml::submitButton(Yii::t('messages', 'Отмена'),array('class'=>'greybutton')); ?>
        <?=CHtml::endForm()?>
    </div>



</div><!-- form -->
</div>