    <?php 
    CHtml::link(Yii::t('auth', 'Не зарегистрированы?'),array('/register'), array('class'=>'right margined'));
    Yii::app()->clientScript->registerPackage("jquery");
    
    Yii::app()->clientScript->registerPackage("jquery.ui");
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.mousewheel.js", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);
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
            <h1 class="left"><?= Yii::t('auth', 'Вход') ?></h1>
            <?php echo CHtml::link(Yii::t('auth', 'Регистрация'),array('/register'), array('class'=>'right margined upcase')); ?>

		
            
        </div>
    </div>
        <div class="separator full-center"></div>
				
		 <div class="row">
			<p class="label-left"/>
			<div id="errorrep" class="full-center"><?$this->widget('Flashes'); ?></div>
		</div>
				
            
        <div class="row">
		<label class="label-left" for="LoginForm_username"><?= Yii::t('auth', 'Email') ?></label>
		<?php echo $form->textField($model,'username',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'username',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
		<label class="label-left" for="LoginForm_username"><?= Yii::t('auth', 'Пароль') ?></label>
		<?php echo $form->passwordField($model,'password',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'password',array('class'=>'line-error')); ?>
	</div>

    <div class="separator full-center"></div>

    <div class="row">
        <div class="label-left"></div>
        <div class="full-center right-align">
            <?php echo CHtml::link(Yii::t('auth', 'Восстановить пароль'),array('auth/restore')); ?>
            <br /><br />
        </div>
    </div>
        
		<?/*<div class="captcha row">

        <?$this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
        <label class="label-captcha label-left" for="LoginForm_verifyCode"><?= Yii::t('payment', 'Введите код на картинке') ?></label>
        <?php echo $form->textField($model, 'verifyCode',array("class"=>"captcha-input input-full","autocomplete"=>"off"))?>
                <?php echo $form->error($model,'verifyCode',array('class'=>'line-error')); ?>
        </div>*/?>
        <!--div class="captcha_renew"></div-->
        <div class="label-left"></div>
        <div class="full-center">
            <div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('auth', 'Авторизация')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>
</div><!-- form -->

<script type="text/javascript">
	$(document).ready(function(){
		if ($('#errorrep').html().length == 0){
			$('#errorrep').parent().empty();
		}
	});
</script>