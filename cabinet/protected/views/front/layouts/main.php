<?php $this->beginContent('//layouts/cabinet'); ?>
<div class="left">
        <div class="padmenu shadowed">
    <?=
CHtml::link('
    <div class="menuitem">
        <div class="text" id="put">'. Yii::t('payment', 'Пополнить') .'
            <p class="label">'. Yii::t('payment', 'Пополнение счета') .'</p>
        </div>
    </div>',array('/payment/payment'))?>
    <?=
CHtml::link('
    <div class="menuitem">
        <div class="text" id="get">'. Yii::t('payment', 'Вывести') .'
            <p class="label">'. Yii::t('payment', 'Вывод средств') .'</p>
        </div>
    </div>',array('/payout/'))?>
    <?=
CHtml::link('
    <div class="menuitem">
        <div class="text" id="switch">'. Yii::t('payment', 'Внутренний перевод') .'
            <p class="label">'. Yii::t('payment', 'Оборот средств между счетами') .'</p>
        </div>
    </div>',array('/account/transfer'))?>
    <?=
    CHtml::link('
    <div class="menuitem">
        <div class="text" id="tradeHistory">'. Yii::t('trade', 'История торговли') .'
            <p class="label">'. Yii::t('trade', 'История торговых операций') .'</p>
        </div>
    </div>',array('/trade/history'))?>
        </div>
    <?php $this->renderPartial('//banners/list') ?>
</div>
	<?php /*if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif*/?>

	<?php echo $content; ?>
<?php $this->endContent(); ?>