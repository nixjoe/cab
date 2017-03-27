<?php
//$this->pageTitle=Yii::app()->name . ' - Login';
//$this->breadcrumbs=array(
//	'Login',
//);
?>
    <?php 
	CHtml::link(Yii::t('auth', 'Уже зарегистрированы?'),array('/register'), array('class'=>'right margined'));
    Yii::app()->clientScript->registerPackage("jquery");
    Yii::app()->clientScript->registerPackage("jquery.ui");
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui-autocomplete.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.select-to-autocomplete.js", CClientScript::POS_HEAD);
       Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.mousewheel.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);
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
            <?php echo CHtml::link(Yii::t('auth', 'Уже зарегистрированы?'),array('login'), array('class'=>'right margined')); ?>
	    <div id="select_language" class="margin-top right">
	    <?

			switch ($_GET['language']){
				case 'ua':
					$lang = 'UKR';
					break;
				case 'ru':
					$lang = 'RUS';
					break;
				case 'en':
					$lang = 'ENG';
					break;
			}
	?> 
	   <div class="rel-container select-container langs">
            <div class="valuebox"><p><?=$lang?></p>
            </div>
            <div class="radiobox">
                <div class="optcontainer">
			<input id="languages" type="hidden" value="ru" name="language" />
			<span id="MsgMessages_assignee">
				<input class="" class="langsel" id="languageru" value="ru" type="radio" name="languageru" /> 
				<label for="languageru">RUS</label>
			</span>
			<input id="languages" type="hidden" value="en" name="language" />
			<span id="MsgMessages_assignee">
				<input class=""  class="langsel"  id="languageen" value="en" type="radio" name="languageen" /> 
				<label for="languageen">ENG</label>
			</span>
			<input id="languages" type="hidden" value="ua" name="language" />
			<span id="MsgMessages_assignee">
				<input class=""  class="langsel"  id="languageua" value="ua" type="radio" name="languageua" /> 
				<label for="languageua">UKR</label>
			</span>
                </div>
            </div>
        </div>
					      
            </div>
        </div>
    </div>
        <div class="separator full-center"></div>
        <div class="row">
		<?php echo $form->label($model,'familyName',array('class'=>'label-left')); ?>
		<?php echo $form->textField($model,'familyName',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'familyName',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
		<?php echo $form->label($model,'givenName',array('class'=>'label-left')); ?>
		<?php echo $form->textField($model,'givenName',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'givenName',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
		<?php echo $form->label($model,'middleName',array('class'=>'label-left')); ?>
		<?php echo $form->textField($model,'middleName',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'middleName',array('class'=>'line-error')); ?>
	</div>        
        <div class="separator full-center"></div>        
        <div class="row">
		<?php echo $form->label($model,'sex',array('class'=>'label-left')); ?>
                    <div class="full-center">
                        <?php echo $form->radioButtonList($model,'sex',array('1'=>'Мужской','0'=>'Женский'),array('separator'=>'&nbsp;&nbsp;&nbsp;')); ?>
                    </div>
		<?php echo $form->error($model,'sex',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
                <?php echo $form->label($model,'birthDate',array('class'=>'label-left')); ?>
            <div class="rel-container">
		<?php echo $form->label($model,'birthDay',array('class'=>'label-overlay')); ?>
		<?php echo $form->textField($model,'birthDay',array('class'=>' overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
                <?php echo $form->label($model,'birthMonth',array('class'=>'label-overlay')); ?>
		<?php echo $form->textField($model,'birthMonth',array('class'=>'overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
		<?php echo $form->label($model,'birthYear',array('class'=>'label-overlay')); ?>
		<?php echo $form->textField($model,'birthYear',array('class'=>'overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
		<?php echo $form->error($model,'birthMonth',array('class'=>'line-error error-overlay')); ?>
                <?php echo $form->error($model,'birthDay',array('class'=>'line-error error-overlay')); ?>            
		<?php echo $form->error($model,'birthYear',array('class'=>'line-error error-overlay')); ?>            
            </div>
	</div>
        <div class="separator full-center"></div>        
        <div class="row">
                <?php echo $form->label($model,'country',array('class'=>'label-left')); ?>
            <div class="rel-container">
                <?php echo $form->label($model,'country', array('class'=>'label-overlay')); ?>
		<select class="midleft-input ui-autocomplete overlayed" name="Users[country]" id="Users_country">
                    <option value="" selected="selected"></option>
                    <?php 
                        $a = CHtml::listData($countrylist,'isoID','rus');
                        foreach ($a as $key=>$value) {
                            echo "<option value='$key'>$value</option>";
                        }
                    ?>
                </select>
                <?php 
                //array_unshift($b,'123');
                $form->dropDownList($model,'country',CHtml::listData($countrylist,'isoID','rus'),array('class'=>'midleft-input ui-autocomplete overlayed',)); ?>
            </div>
            <div class="rel-container">
                <?php echo $form->label($model,'city', array('class'=>'label-overlay')); ?>
		<?php echo $form->textField($model,'city',array('class'=>'midright-input overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
		<?php echo $form->error($model,'city',array('class'=>'line-error error-overlay')); ?>
                <?php echo $form->error($model,'country',array('class'=>'line-error error-overlay')); ?>
            </div>
	</div>
       <div class="row">
		<?php echo $form->label($model,'address',array('class'=>'label-left')); ?>
		<?php echo $form->textField($model,'address',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'address',array('class'=>'line-error')); ?>
	</div>
       <div class="row">
		<?php echo $form->label($model,'phone',array('class'=>'label-left')); ?>
		<?php echo $form->textField($model,'phone',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'phone',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
		<?php echo $form->label($model,'email',array('class'=>'label-left')); ?>
		<?php echo $form->textField($model,'email',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'email',array('class'=>'line-error')); ?>
        </div>
        <div class="separator full-center"></div>          
	<div class="row">
                <?php echo $form->label($model,'password',array('class'=>'label-left')); ?>
            <div class="rel-container">
                <?php echo CHtml::label(Yii::t('auth', 'Пароль'), 'Users_password',array('class'=>'label-overlay'))
                //echo $form->label($model,'password',array('class'=>'label-overlay')); ?>
		<?php echo $form->passwordField($model,'password',array('class'=>'midleft-input overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
		<?php echo CHtml::label(Yii::t('auth', 'Подтверждение пароля'), 'Users_passConfirm',array('class'=>'label-overlay'))
                //echo $form->label($model,'passConfirm',array('class'=>'label-overlay')); ?>
		<?php echo $form->passwordField($model,'passConfirm',array('class'=>'midright-input overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
		<?php echo $form->error($model,'passConfirm',array('class'=>'line-error error-overlay')); ?>
                <?php echo $form->error($model,'password',array('class'=>'line-error error-overlay')); ?>
             </div>
	</div>
        <div class="row">
                <?php echo $form->label($model,'phonePassword',array('class'=>'label-left')); ?>
            <div class="rel-container">
                <?php echo CHtml::label(Yii::t('auth', 'Пароль'), 'Users_phonePassword',array('class'=>'label-overlay'))
                //echo $form->label($model,'phonePassword',array('class'=>'label-overlay')); ?>
		<?php echo $form->passwordField($model,'phonePassword',array('class'=>'midleft-input overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
		<?php echo CHtml::label(Yii::t('auth', 'Подтверждение пароля'), 'Users_phonePassConfirm',array('class'=>'label-overlay'))
                //echo $form->label($model,'phonePassConfirm',array('class'=>'label-overlay')); ?>
		<?php echo $form->passwordField($model,'phonePassConfirm',array('class'=>'midright-input overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
		<?php echo $form->error($model,'phonePassConfirm',array('class'=>'line-error error-overlay')); ?>
                <?php echo $form->error($model,'phonePassword',array('class'=>'line-error error-overlay')); ?>
            </div>
	</div>
        <div class="separator full-center"></div>                  
        <div class="captcha">

            <?$this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
        <?=$form->label($model, 'verifyCode',array("class"=>"label-captcha label-left"))?>
        <?=$form->textField($model, 'verifyCode',array("class"=>"captcha-input input-full","autocomplete"=>"off"))?>
        <!--div class="captcha_renew"></div-->
        <?php echo $form->error($model,'verifyCode',array('class'=>'line-error ')); ?>
        </div>
        <div class="label-left"></div>
        <div class="full-center">
            <div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('auth', 'Зарегистрироваться')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>
</div><!-- form -->
