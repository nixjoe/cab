    <?php 
    CHtml::link(Yii::t('auth', 'Уже зарегистрированы?'),array('/register'), array('class'=>'right margined'));    
    Yii::app()->clientScript->registerPackage("jquery");
    Yii::app()->clientScript->registerPackage("jquery.ui");
    //Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.ui.widget.js", CClientScript::POS_HEAD);    
    //Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.ui.position.js", CClientScript::POS_HEAD);
    //Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.ui.selectmenu.js", CClientScript::POS_HEAD);
    //Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jstyling.pack.js", CClientScript::POS_HEAD);
    //Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery-ui-autocomplete.js", CClientScript::POS_HEAD);
    //Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.select-to-autocomplete.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.mousewheel.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/relcontainer.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.tooltip.min.js", CClientScript::POS_END);
    
    Yii::app()->clientScript->registerScript('inend_register',"

    //$('#Users_country').selectToAutocomplete({});
// Код для подстановки кода телефона после выбора страны:
    $('.select-container .radiobox :radio[name=\"Users[country]\"]').live('change',function(){
        $('#dialcode').val('+' + phonecodes[$(this).filter(':checked').val()]);
        $('#Users_dialcode').val(phonecodes[$(this).filter(':checked').val()]);
    });
    if ($('.select-container .radiobox :radio[name=\"Users[country]\"]').filter(':checked').val() != undefined)
        $('#dialcode').val('+' + phonecodes[$('.select-container .radiobox :radio[name=\"Users[country]\"]').filter(':checked').val()]);
    $(document).ready(function() {
        $('#dialcode').val('+' + phonecodes[$('.select-container .radiobox :radio[name=\"Users[country]\"]').filter(':checked').val()]);
        $('#Users_dialcode').val(phonecodes[$('.select-container .radiobox :radio[name=\"Users[country]\"]').filter(':checked').val()]);

        $('.field-info-btn').tooltip({
            bodyHandler: function() {
                return $(this).next('.field-info').html() || '<div></div>';
            }
        });
    });
",  CClientScript::POS_END);
    Yii::app()->clientScript->registerScript('read_register',"
    //$.jStyling.createSelect($('select#Users_birthMonth'));   
",  CClientScript::POS_READY);
    $months = array(  // При добавлении многоязычности - перенести в файл с меседжами
        1=>Yii::t('month', 'Январь'),
        2=>Yii::t('month', 'Февраль'),
        3=>Yii::t('month', 'Март'),
        4=>Yii::t('month', 'Апрель'),
        5=>Yii::t('month', 'Май'),
        6=>Yii::t('month', 'Июнь'),        
        7=>Yii::t('month', 'Июль'),
        8=>Yii::t('month', 'Август'),
        9=>Yii::t('month', 'Сентябрь'),
        10=>Yii::t('month', 'Октябрь'),
        11=>Yii::t('month', 'Ноябрь'),
        12=>Yii::t('month', 'Декабрь'),
        );
     for($i = (date('Y') - 18 + (date('n') >= 10 ? 1 : 0)); $i > 1929; $i--) {
    		$years[$i] = $i;
    }
?>
<script type="text/javascript">
// Массив с телефонными кодами:
var phonecodes = new Array();
<?php foreach ($countrylist as $k=>$v) {
    echo "phonecodes[{$v['isoID']}] = {$v['dialcode']};";
}
?>
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
        <div  class="row">
            <p style="margin:0" class="label-left"/>
            <div class="full-center">
                <?php $this->widget('Flashes'); ?>
            </div>
        </div>
        <div class="row">
      <?php echo CHtml::label(Yii::t('reg', 'Фамилия'), "familyName", array('class'=>'label-left'));?>
		<?php echo $form->textField($model,'familyName',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'familyName',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
		<?php echo CHtml::label(Yii::t('reg', 'Имя'), "givenName", array('class'=>'label-left'));?>
		<?php echo $form->textField($model,'givenName',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'givenName',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
		<?php echo CHtml::label(Yii::t('reg', 'Отчество'), "middleName", array('class'=>'label-left'));?>
		<?php echo $form->textField($model,'middleName',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'middleName',array('class'=>'line-error')); ?>
	</div>
        <div class="separator full-center"></div>
		<div class="row">
		<?php echo CHtml::label(Yii::t('reg', 'Пол'), "sex", array('class'=>'label-left'));?>
                    <div class="full-center">
						<!-- ниже строчку закомместить -->
						<?php echo $form->radioButtonList($model,'sex',array('1'=>Yii::t('auth', 'Мужской'),'0'=>Yii::t('auth', 'Женский')),array('separator'=>'&nbsp;&nbsp;&nbsp;')); ?>
						<!-- тут разместить -->
					</div>
		
		<?php echo $form->error($model,'sex',array('class'=>'line-error')); ?>
	</div>
        <div class="row">
                <?php echo CHtml::label(Yii::t('reg', 'Дата рождения'), "birthDay", array('class'=>'label-left'));?>
            <div class="rel-container">
		<?php echo $form->label($model,'birthDay',array('class'=>'label-overlay')); ?>
		<?php echo $form->textField($model,'birthDay',array('class'=>' overlayed','autocomplete'=>'off')); ?>
            </div>
            <div class="select-container rel-container" id="select-birthMonth">
                <div class="valuebox"><p><?php echo CHtml::label(Yii::t('reg', 'Месяц'), "birthMonth");?></p>
                </div>
                <div class="radiobox">
                    <div class="optcontainer">
                    <?=$form->radioButtonList($model,'birthMonth',$months,array(
                        'class'=>'',
                        'separator'=>''
                        ))?>
                    </div>
                </div>
            </div>
            <?php //$form->dropDownList($model,'birthMonth',$months,array('class'=>'overlayed',))?>
            <?php //echo $form->textField($model,'birthMonth',array('class'=>'overlayed','autocomplete'=>'off')); ?>

		<?php //echo $form->label($model,'birthYear',array('class'=>'label-overlay')); ?>
		<?php //echo $form->textField($model,'birthYear',array('class'=>'overlayed','autocomplete'=>'off')); ?>
            <div class="select-container rel-container" id="select-birthYear">
                <div class="valuebox"><p><?php echo CHtml::label(Yii::t('reg', 'Год'), "birthYear");?></p>
                </div>
                <div class="radiobox">
                    <div class="optcontainer scrollable">
                    <?=$form->radioButtonList($model,'birthYear',$years,array(
                        'class'=>'',
                        'separator'=>''
                        ))?>
                    </div>
                </div>
            </div>

            <div class="rel-container">
		<?php echo $form->error($model,'birthMonth',array('class'=>'line-error error-overlay')); ?>
                <?php echo $form->error($model,'birthDay',array('class'=>'line-error error-overlay')); ?>            
		<?php echo $form->error($model,'birthYear',array('class'=>'line-error error-overlay')); ?>            
            </div>
	</div>
        <div class="separator full-center"></div>
        <div class="row">
                <?php echo CHtml::label(Yii::t('auth', 'Место проживания'), "Users_country", array('class'=>'label-left'));?>
            <div class="select-container rel-container midleft-input" id="select-country">
                <div class="valuebox"><p><?=$form->label($model,'country',array('class'=>'label-select'));?></p>
                </div>
                <div class="radiobox">
                    <div class="optcontainer scrollable">
                    <?
                                      if(Yii::app()->language == 'ru') $lng = 'rus';
                    							else $lng = 'eng'; 
                    							
                     $model->country = $user_country; echo $form->radioButtonList($model,'country',CHtml::listData($countrylist,'isoID',$lng),array(
                        'class'=>'',
                        'separator'=>'',
                        'template'=>'{input}{label}'
                        ))?>
                    </div>
                </div>
            </div>
            <div class="rel-container">
                <?php if (false) {echo $form->label($model,'country', array('class'=>'label-overlay')); ?>
		<select class="midleft-input ui-autocomplete overlayed" name="Users[country]" id="Users_country">
                    <option value="" selected="selected"></option>
                    <?php 
                   
                        $a = CHtml::listData($countrylist,'isoID',$lng);
                        foreach ($a as $key=>$value) {
                            echo "<option value='$key'>$value</option>";
                        }
                    ?>
                </select>
                <?php 
                //array_unshift($b,'123');
                $form->dropDownList($model,'country',CHtml::listData($countrylist,'isoID',$lng),array('class'=>'midleft-input ui-autocomplete overlayed',)); }?>
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
            <?php echo CHtml::label(Yii::t('reg', 'Телефон'), "phone", array('class'=>'label-left'));?>
            <div class="rel-container">
		<?php echo CHtml::textField('dialcode', '+...', array('class'=>'','autocomplete'=>'off','readonly'=>'readonly'));?>
                <?php echo $form->hiddenField($model,'dialcode');?>
            </div>
            <div class="rel-container">
                <?php echo $form->textField($model,'phone',array('class'=>'','autocomplete'=>'off')); ?>
            </div>
            <div class="rel-container">
                <?php echo $form->error($model,'dialcode',array('class'=>'line-error error-overlay')); ?>
                <?php echo $form->error($model,'phone',array('class'=>'line-error error-overlay')); ?>
            </div>
	</div>
        <div class="row">
		<?php echo CHtml::label(Yii::t('reg', 'Email'), "email", array('class'=>'label-left'));?>
		<?php echo $form->textField($model,'email',array('class'=>'input-full','autocomplete'=>'off')); ?>
		<?php echo $form->error($model,'email',array('class'=>'line-error')); ?>
        </div>
        <div class="separator full-center"></div>

	<div class="row field-has-info">
        <?php echo CHtml::label(Yii::t('reg', 'Пароль'), "password", array('class'=>'label-left'));?>
        <span class="field-info-btn"></span>
        <div class="field-info" style="display: none">
            <div class="flash notice">
                <p class="close"></p>
                <div class="header">
                    <?php echo Yii::t('reg', 'Пароль Вашей авторизации в Личном кабинете') ?><br />
                    my.fx-private.com
                </div>
            </div>
        </div>
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

    <div class="row field-has-info">
        <?php echo CHtml::label(Yii::t('reg', 'Телефонный пароль'), "phonePassword", array('class'=>'label-left'));?>
        <span class="field-info-btn"></span>
        <div class="field-info" style="display: none">
            <div class="flash notice">
                <p class="close"></p>
                <div class="header">
                    <?php echo Yii::t('reg', 'Пароль аутентификации владельца счета для телефонного диллинга Введите пароль два раза') ?>
                </div>
            </div>
        </div>
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

    <div class="row">
        <label class="label-left">&nbsp;</label>
        <div class="rel-container">
            <?php echo $form->checkBox($model,'subscribe');?>
            <?php echo CHtml::label(Yii::t('reg', 'Я согласен получать новости компании по почте'), 'Users_subscribe') ?>
        </div>
        <?php echo $form->error($model,'subscribe',array('class'=>'line-error error-overlay')); ?>
    </div>
    <br />

<?/* if (false)  {?>
        <div class="captcha">

         <?$this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
        <?=$form->label($model, 'verifyCode',array("class"=>"label-captcha label-left"))?>
        <?=$form->textField($model, 'verifyCode',array("class"=>"captcha-input input-full","autocomplete"=>"off"))?>
        <!--div class="captcha_renew"></div-->
        <?php echo $form->error($model,'verifyCode',array('class'=>'line-error ')); ?>
        </div>
<? } */?>
        <div class="label-left"></div>
        <div class="full-center">
            <div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('auth', 'Зарегистрироваться')); ?>
            </div>
        </div>

<?php $this->endWidget(); ?>
</div><!-- form -->



