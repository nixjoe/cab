
<div class="cnt left">
    <h3><?= Yii::t('payment', 'Пополнение счета') ?></h3>
    <?=CHtml::beginForm()?>

<div class="half-width right-marged content-box">
	<?if (!isset($_GET['target'])){?>
    <div class="shadowed padtable" rules="rows" bordercolor="blue" border="1" frame="void">

        <div class="headrow"><div class="form"><?=CHtml::activeLabel($payform, 'target')?></div></div>
		<?php
		if (!empty($data)) {
			echo CHtml::activeRadioButtonList($payform, 'target', $data, array(
			'template'=>'<div class="trow">{input}{label}</div>',
			'encode'=>false,
			'separator'=>''
			));
			}else
		{
			echo Yii::t('payment', "Нет счетов для отображения");
		}
		?>

    </div>
	<?}else{?>
			<input type="hidden" name="PaymentForm[target]" value="<?=isset($_GET['target']) ? $_GET['target'] : 0?>" />
	<?}?>
</div>
<div class="form left">
    <br/><?=CHtml::activeLabel($payform, 'ik_payment_amount')?>
<div class="row">
    <?=CHtml::activeTextField($payform, 'ik_payment_amount', array('size'=>'47', 'autocomplete'=>'off'))?>
</div>
<div class="row buttons">
<?php echo CHtml::submitButton(Yii::t('payment', 'Перейти к выбору способа оплаты')); ?>
<input style="margin-left:10px;" type="button" value="<?= Yii::t('payment', 'Уведомить о платеже') ?>" class="redirectbutton greybutton">
<?/*&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?= Yii::app()->createUrl('account/notify') ?>">Уведомить о платеже</a>*/?>
</div>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.redirectbutton').click(function(){
			window.location = '/account/notify';
		});
	});
</script>