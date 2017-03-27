<div class="cnt left">
    <h3><?= Yii::t('partner', 'PARTNER_ACCOUNT') ?></h3>
    <div class="separator"></div>

    <?php $this->widget('Flashes'); ?>

    <div class="partnerForm">
        <?= CHtml::beginForm(Yii::app()->createUrl('partner/new')); ?>
        <div id="partnerStartForm">
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
                <?= CHtml::hiddenField('accept', '1') ?>
                <?= CHtml::submitButton(Yii::t('main', 'Открыть счет'),array('class'=>'greenbutton')) ?>
            </div>
        </div>
        <?= CHtml::endForm(); ?>
    </div>
</div>