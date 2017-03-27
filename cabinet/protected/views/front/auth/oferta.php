    <?php 

    CHtml::link(Yii::t('auth', 'Уже зарегистрированы?'),array('/login'), array('class'=>'right margined'));
    Yii::app()->clientScript->registerPackage("jquery");
    Yii::app()->clientScript->registerPackage("jquery.ui");
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui-autocomplete.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.select-to-autocomplete.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScript('onready1',"
         $('.overlayed').focus(function(){
         $(this).siblings('.label-overlay').hide();
    });
    $('.overlayed').blur(function(){
        if ($(this).val()=='') $(this).siblings('label.label-overlay').show();
    });
    $('.label-overlay').click(function(){
        $(this).hide();
        $(this).siblings('.ui-autocomplete').focus();
    });
    if ($('input.overlayed').val()!=='') $('input.overlayed').siblings('label').hide();
    $('#Users_country').selectToAutocomplete({

        
    });
",  CClientScript::POS_END);
?>
<script type="text/javascript">


</script>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'register-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

    <div class="row">
        <div class="label-left"></div>
        <div class="full-center">
            <h1 class="left"><?= Yii::t('auth', 'Регистрация') ?></h1>
            <?php echo CHtml::link(Yii::t('auth', 'Уже зарегистрированы?'),array('/login'), array('class'=>'right margined')); ?>
        </div>
    </div>
        <div class="separator full-center"></div>
        <div class="row">
            <div class="label-left"></div>
            <div class="full-center">
                <?= Yii::t('auth', 'Пожалуйста, ознакомьтесь с приведенными ниже положениями клиентского соглашения') ?>:<br/><br/>
                <ol>
                		<?php              	
                		if($_GET['language'] == 'ua') Yii::app()->language = 'ru'; if($_GET['language'] != 'ua' && $_GET['language'] != 'ru') Yii::app()->language = 'en';?>
                    <li><?=CHtml::link(Yii::t('auth', 'Клиентское соглашение'),'/files/Customer_agreement.pdf',array('target'=>'_blank'))?></li>
                    <li><?=CHtml::link(Yii::t('auth', 'Регламент торговых операций'),'/files/Terms_of_Business.pdf',array('target'=>'_blank'))?></li>
                    <li><?=CHtml::link(Yii::t('auth', 'Регламент торговых операций').'  ECN','/files/Terms_of_Business_ECN_'.(Yii::app()->language).'.pdf',array('target'=>'_blank'))?></li>
                    <li><?=CHtml::link(Yii::t('auth', 'Уведомление о рисках'),'/files/Notification_of_risk.pdf',array('target'=>'_blank'))?></li>
                    <li><?=CHtml::link(Yii::t('auth', 'Политика противодействия отмыванию денежных средств'),'/files/Anti-money_laundering_policy.pdf',array('target'=>'_blank'))?></li>
                    <li><?=CHtml::link(Yii::t('auth', 'Термины и определения'),'/files/Terms_and_definitions.pdf',array('target'=>'_blank'))?></li>
 							
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="label-left"></div>
            <div class="full-center ">
                <?php echo $form->checkBox($oferta,'agreed');
                ?>
                <label for="OfertaForm_agreed"><?= Yii::t('reg', 'Ознакомился и согласен') ?></label>
            </div>
            <?php echo $form->error($oferta,'agreed',array('class'=>'line-error')); ?>
            <?php Yii::app()->language = $_GET['language']; if($_GET['language'] == 'ua') Yii::app()->language = 'uk'; if($_GET['language'] == 'cn') Yii::app()->language = 'zh_cn'; ?>
	</div>

    <div class="captcha">
        <?php $this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
        <label class="label-captcha label-left" for="OfertaForm_verifyCode"><?= Yii::t('payment', 'Введите код на картинке') ?></label>
        <?php echo $form->textField($oferta, 'verifyCode',array("class"=>"captcha-input input-full","autocomplete"=>"off"))?>
        <!--div class="captcha_renew"></div-->
        <?php echo $form->error($oferta,'verifyCode',array('class'=>'line-error ')); ?>
    </div>
		
        <div class="separator full-center"></div>                  
        <div class="label-left"></div>
        <div class="full-center">
            <div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('auth', 'Далее')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
