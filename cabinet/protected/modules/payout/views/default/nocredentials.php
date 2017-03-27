<div class="cnt left">
    <h3><?= Yii::t('payout', 'Платежные реквизиты отсутствуют') ?></h3>
    <div class="separator"></div>
    <?= Yii::t('payout', 'В данный момент у Вас нет проверенных платежных реквизитов.') ?> <br/>
    <?=CHtml::link(Yii::t('payout', 'Нажмите тут, чтобы добавить.'), array('default/newcredentials'))?>
</div>