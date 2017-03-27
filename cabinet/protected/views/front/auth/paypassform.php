    <?php 
    
    Yii::app()->clientScript->registerPackage("jquery");
    Yii::app()->clientScript->registerPackage("jquery.ui");
    Yii::app()->clientScript->registerScript('onready1',"
         $('input.overlayed').focus(function(){
        $(this).siblings('label.label-overlay').hide();
    });
    $('input.overlayed').blur(function(){
        if ($(this).val()=='') $(this).siblings('label.label-overlay').show();
    });
    if ($('input.overlayed').val()!=='') $('input.overlayed').siblings('label').hide();
//    $('input:-webkit-autofill.overlayed').siblings('label.label-overlay').hide();    
",  CClientScript::POS_END);
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

    <div class="row">
        <div class="label-left"></div>
        <div class="full-center">
            <h1 class="left"><?= Yii::t('auth', 'Платежный пароль') ?></h1>
        </div>
    </div>
    
    <div class="separator full-center"></div>
    <div class="row">
        <div class="label-left"></div>
        <div class="full-center">
			 <?php $this->widget('Flashes'); ?>
            <?/* <span class="left">Перед началом работы необходимо создать пароль, который будет использоваться при проведении финансовых операций, требующих повышенной защиты:</span>*/?>
        </div>
    </div>        
        <div class="row">
		<?php echo $form->label($paypassform,'paymentPassword',array('class'=>'label-left')); ?>
		<?php echo $form->passwordField($paypassform,'paymentPassword',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($paypassform,'paymentPassword',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
		<?php echo $form->label($paypassform,'paymentPassConfirm',array('class'=>'label-left')); ?>
		<?php echo $form->passwordField($paypassform,'paymentPassConfirm',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($paypassform,'paymentPassConfirm',array('class'=>'line-error')); ?>
	</div>
        <div class="separator full-center"></div>                  
        <!--div class="captcha_renew"></div-->
        <div class="label-left"></div>
        <div class="full-center">
            <div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('auth', 'Продолжить')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>
</div><!-- form -->