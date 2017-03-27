<div class="cnt left">
    <h3><?= Yii::t('partner', 'PARTNER_ACCOUNT') ?></h3>
    <div class="separator"></div>

    <?php $this->widget('Flashes'); ?>

    <table class="shadowed padtable content-box">
        <tr class="headrow">
            <th><?= Yii::t('account', 'Номер счета') ?></th>
            <th><?= Yii::t('account', 'Тип счета') ?></th>
            <th><?= Yii::t('account', 'Баланс') ?></th>
            <th><?= Yii::t('account', 'Плечо') ?></th>
            <th width="125"><?= Yii::t('account', 'Изменить плечо') ?></th>
        </tr>
        <tr class="trow">
            <td class="curr"><?= $account->mtID ?></td>
            <td><?= $account->fxType_->name ?></td>

            <?php if ($mtdata) {
                    $tdClass = null;
                    $balance = $mtdata['balance'];
                    $leverage = $mtdata['leverage'];
            }else {
                $tdClass = 'cahced';
                $balance = $account->amount;
                $leverage = $account->leverage;
            }?>

            <td<?= $tdClass ? ' class="cached"':'' ?>>
                <?= round($balance,2) ?>
                <?php
                    echo CHtml::link(' ', array('/payout'), array('class'=>'padminus right'));
                    echo CHtml::link(' ', array('/payment/payment', 'target' => $account->mtID), array('class'=>'padplus right'));
                ?>
            </td>
            <td<?= $tdClass ? ' class="cached"':'' ?>>1:<?= $leverage ?></td>

            <td>
                <?= CHtml::link(Yii::t('account', 'Изменить плечо'), $this->createUrl('/account/edit', array('id'=>$account->ID)), array('class'=>'padgreybutton')) ?>
            </td>
        </tr>
    </table>
</div>
