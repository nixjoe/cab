<div class="form">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'restore-form',
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

    <div class="row">
        <div class="label-left"></div>
        <div class="full-center">
            <h3><?= Yii::t('auth', 'Восстановление пароля личного кабинета') ?></h3>
        </div>
    </div>

    <div class="separator full-center"></div>

    <div class="row">
        <div class="label-left"></div>
        <div class="full-center">
            <?= Yii::t('auth', 'Пожалуйста, чтобы восстановить к Личному Кабинету ForexPrivate введите адрес электронной почты, который Вы вводили при регистрации Личного Кабинета и нажмите "Восстановить"') ?>
        </div>
        <p>&nbsp;</p>
    </div>

    <div class="row">
        <p class="label-left"/>
        <div class="full-center"><?$this->widget('Flashes'); ?></div>
    </div>

    <div class="row">
        <label class="label-left" for="LoginForm_email"><?= Yii::t('auth', 'Email') ?></label>
        <?php echo $form->textField($model,'username',array('class'=>'input-full','autocomplete'=>'off')); ?>
        <?php echo $form->error($model,'username',array('class'=>'line-error')); ?>
    </div>
    <?php if(CCaptcha::checkRequirements()): ?>
        <div class="captcha">
            <?php $this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
            <label class="label-captcha label-left" for="ResetForm_verifyCode"><?= Yii::t('payment', 'Введите код на картинке') ?></label>
            <?php echo $form->textField($model, 'verifyCode',array("class"=>"captcha-input input-full","autocomplete"=>"off"))?>
            <?php echo $form->error($model,'verifyCode',array('class'=>'line-error ')); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="label-left"></div>
        <div class="full-center">
            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('auth', 'Восстановить')); ?>
            </div>
        </div>
    </div>

    <?php $this->endWidget(); ?>

</div>