<?php
    Yii::app()->clientScript->registerScript(
        'partner',
        '
            $("#partnerBtn").on("click", function(e){
                e.preventDefault();
                $(this).toggleClass("greybutton");
                $(".partnerForm").toggle();
            });
            
            $("#partnerAccept").on("change", function(e){
                var checked = $(this).is(":checked");
                $("#partnerStartForm").toggle(checked);
            });
            
            $("#verifyBtn").on("click", function(e){
                e.preventDefault();
                $(this).toggleClass("greybutton");
                $(".phone-verification").toggleClass("foldable");
                if ( !$(".add-phone").hasClass("foldable") ){
                     $(".add-phone").toggleClass("foldable");
                }
            });
            
            $("#addPhoneBtn").on("click", function(e){
                e.preventDefault();
                $(this).toggleClass("greybutton");
                $(".add-phone").toggleClass("foldable");
                if ( !$(".phone-verification").hasClass("foldable") ){
                     $(".phone-verification").toggleClass("foldable");
                }
            });
            
        ',
        CClientScript::POS_END
    );
?>

<div class="cnt left">
    <h3><?= Yii::t('payment', 'Кошелек FXPRIVATE:') ?> <span>FX<?= $user->transitID ?></span>
        <p class="certificate <?= $class ?>" title="<?= $title ?>"></p>
    </h3>
    <p class="separator"></p>
    <?php $this->widget('Flashes'); ?>
    <h5>
        <?= Yii::t('payment', 'Имя') ?>:
        <span><?= $user->familyName ?> <?= $user->givenName ?> <?= $user->middleName ?></span>
    </h5>
    <h5>
        <?= Yii::t('payment', 'Телефон') ?>: <span><?= $user->phone ?></span>
        <? if ($user->ID == 27){?>
            <?= CHtml::link(Yii::t('payment', "Подтвердить"), array("/profile"), array('class' => 'greenbutton right-marged', 'style' => 'margin-left:10px', 'id'=>'verifyBtn')) ?>
            <?= CHtml::link(Yii::t('payment', "Изменить"),  array("/profile"), array('class' => 'greenbutton right-marged', 'id'=>'addPhoneBtn')) ?>
        <?}?>
    </h5>
    <div class="phone-verification foldable form">
        <label for="phone_verify"><?= Yii::t('payment', 'Введите код с СМС') ?></label>
        <div class="row">
            <input size="5" autocomplete="off" name="sms" id="phone_verify" type="text">
            <div class="row buttons" style="display: inline-block; margin-left: 10px;">
                <?php echo CHtml::submitButton(Yii::t('messages', 'Ввести')); ?>
            </div>
        </div>
    </div>
    <div class="add-phone foldable form">
        <label for="phone_verify"><?= Yii::t('payment', 'Введите номер телефона в формате: +380') ?></label>
        <div class="row">
            <input size="15" autocomplete="off" name="sms" id="new_phone_number" type="text">
            <div class="row buttons" style="display: inline-block; margin-left: 10px;">
                <?php echo CHtml::submitButton(Yii::t('messages', 'OK')); ?>
            </div>
        </div>
    </div>
    <h5><?= Yii::t('payment', 'email') ?>: <span><?= $user->email ?></span></h5>
    <h5><?= Yii::t('payment', 'login') ?>: <span><?= $user->email ?></span></h5>

    <?= CHtml::link(Yii::t('payment', "Обновить"), array("/profile"), array('class' => 'greenbutton right-marged')) ?>
    <?= CHtml::link(Yii::t('payment', "Изменить"), '/link.php?lang=' . $_GET['language'] . '&slug=change', array('class' => 'greenbutton right-marged', 'target' => '_blank')) ?>
    <?php if ($user->status == 0 || $user->status == 3) {
        echo CHtml::link(Yii::t('payment', 'Пройти аттестацию'), array('/profile/verification'), array('class' => 'greybutton'));
    }
    ?>

    <?php if($user->partner == 0 && $partnerAccType): ?>
        <br /><br />
        <?php echo CHtml::link(Yii::t('payment', 'Стать партнером'), '#', array('class' => 'greenbutton', 'id'=>'partnerBtn')); ?>
        <br /><br />
        <?php if ($user->status == 1): ?>
            <div class="partnerForm" style="display: none;">
                <?= CHtml::beginForm(Yii::app()->createUrl('profile/partner')); ?>
                <div class="shadowed padblock form">
                    <a href="<?= Yii::t('partner', 'AGREEMENT_LINK') ?>"><b><?= Yii::t('partner', 'AGREEMENT') ?></b></a>
                    <div class="separator"></div>
                    <label>
                        <?php
                            echo CHtml::checkBox('accept', false, array('id'=>'partnerAccept'));
                            echo Yii::t('reg', 'Ознакомился и согласен');
                        ?>
                    </label>
                </div>
                <div id="partnerStartForm" style="display: none;">
                    <br /><br />
                    <h3><?= Yii::t('partner', 'OPEN_ACCOUNT') ?></h3>
                    <div class="separator full-center"></div>
                    <h3>
                        <a><?= $partnerAccType->name ?></a>
                    </h3>
                    <br />
                    <div class="shadowed padblock ui-widget-content">
                        <br />
                        <div>
                            <?=Yii::t('account', 'Выберите желаемое кредитное плечо')?>:
                        </div>
                        <br />
                        <?php $lv = $partnerAccType->leverageList(); $lvk = key($lv); reset($lv); ?>
                        <?= CHtml::radioButtonList('leverage',$lvk,$lv,array('separator'=>' '))?><br/><br/>
                        <?= CHtml::hiddenField('fxType', $partnerAccType->ID) ?>
                        <?= CHtml::submitButton(Yii::t('main', 'Открыть счет'),array('class'=>'greenbutton')) ?>
                    </div>
                </div>
                <?= CHtml::endForm(); ?>
            </div>
        <?php else: ?>
            <div class="form partnerForm" style="display: none;">
                <div class="flash error">
                    <p class="close"></p>
                    <div class="header"><?= Yii::t('alert', 'Счет не прошел аттестацию') ?></div>
                    <?= Yii::t('partner', 'NOT_VERIFIED_MSG') ?>
                 </div>
            </div>
        <?php endif;?>
    <?php endif;?>
</div>