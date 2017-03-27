<?php

Yii::app()->clientScript->registerScript('inend1',"
$('#PartnerTransferForm_source :radio, #PartnerTransferForm_target :radio').live('click',function(e){
    if($(this).hasClass('on')){
       $(this).removeAttr('checked');
    }
    $(this).toggleClass('on');
}).filter(':checked').addClass('on');

",  CClientScript::POS_END);

?>
<div class="cnt left">
	<h3><?= Yii::t('partner', 'REPLENISH_CLIENT_ACCOUNT') ?></h3>
	<div class="separator"></div>

    <?php $this->widget('Flashes'); ?>

    <?php if ($this->user->replenish_client): ?>

        <?php $form = $this->beginWidget('CActiveForm', array(
            'id'=>'partner-transfer-form',
            'enableAjaxValidation'=>false,
            'enableClientValidation'=>true,
        )); ?>

        <?= $form->errorSummary($transfer); ?>

        <div class="half-width left right-marged content-box">
            <div class="shadowed padtable" rules="rows" bordercolor="blue" border="1" frame="void">
                <div class="headrow">
                    <div class="form"><?= $form->labelEx($transfer, 'source')?></div>
                </div>
                <?php
                    if ($partnerAccount) {
                        $balance = isset($mtdata[$partnerAccount->mtID]) ? $mtdata[$partnerAccount->mtID]['balance'] : $partnerAccount->amount;
                        $data = array(
                            $partnerAccount->mtID => '
                                    <div class="mtid curr">'.$partnerAccount->mtID.'</div>
                                    <div class="type">'.Yii::t('transfer', 'Исходный счет').'</div>
                                    <div class="ballance">' . floor($balance * 100)/100 . '</div>'
                        );
                        echo $form->radioButtonList($transfer, 'source', $data, array(
                            'template'=>'<div class="trow">{input}{label}</div>',
                            'encode'=>false,
                            'separator'=>''
                        ));
                    } else{
                        Yii::t('account', "Нет счетов для отображения");
                    }
                ?>
            </div>
        </div>

        <div class="half-width left content-box">
            <div class="shadowed padtable half-width left" rules="rows" bordercolor="blue" border="1" frame="void">
                <div class="headrow">
                    <div class="form"><?= $form->labelEx($transfer, 'target')?></div>
                </div>
                <?php
                    if ($targetAccounts) {
                        $data = array();
                        foreach ($targetAccounts as $val) {
                            $mtID = $val->user_->transitID;
                            $balance = $val->amount;
                            $data[$mtID] = '
                                <div class="mtid curr">'.$mtID.'</div>
                                <div class="type">'.Yii::t('account', 'Транзитный счет').'</div>
                                <div>'.(floor($balance * 100)/100).'</div>';
                        }
                        echo $form->radioButtonList($transfer, 'target', $data, array(
                            'template'=>'<div class="trow">{input}{label}</div>',
                            'encode'=>false,
                            'separator'=>''
                        ));
                    } else{
                        Yii::t('account', "Нет счетов для отображения");
                    }
                ?>
            </div>
        </div>
        <div class="form left">
            <br/>
            <?= $form->labelEx($transfer, 'amount')?>
            <div class="row">
                <?= $form->textField($transfer, 'amount')?>
            </div>
            <br />
            <p>
                <?= Yii::t('payout', 'Пожалуйста, введите платежный пароль для подтверждения платежа. <br>
                Он был создан Вами при первом входе в Личный кабинет.') ?>
            </p>
            <?= $form->labelEx($transfer, 'password')?>
            <div class="row">
                <?= $form->passwordField($transfer, 'password')?>
            </div>
            <div class="row buttons">
                <?php echo CHtml::submitButton(Yii::t('messages', 'Отправить')); ?>
            </div>
        </div>
        <?php $this->endWidget(); ?>

    <?php else: ?>

        <form method="POST" action="<?= Yii::app()->createUrl('partner/replenishRequest') ?>">
            <div class="form row buttons">
                <input type="hidden" name="request" value="1" />
                <input type="submit" value="<?= Yii::t('partner', 'REPLENISH_REQUEST') ?>" />
            </div>
        </form>

    <?php endif; ?>
</div>