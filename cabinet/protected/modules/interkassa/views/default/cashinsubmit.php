<script>
	$(document).ready(
		function() {
			$('input[name=ik_paysystem_alias]').change(function() {
				if($(this).val() == 'bank') 
					$('#pay_method_form').attr('action', '<?=Yii::app()->createUrl("profile/TransitBank")?>');
				else
					$('#pay_method_form').attr('action', 'https://interkassa.com/lib/payment.php');
			});	
		}	
	);

</script>
    <?php
    if (sizeof($paysystems)>0)
    foreach ($paysystems as $key=>$val) {
        $data[$key] = "<div>$val</div>";
    }
    ?>
<div class="cnt left">
    <h3>Пополнение счета</h3>

    <?=CHtml::beginForm('https://interkassa.com/lib/payment.php', 'post', array('id'=>'pay_method_form'))?>
<div class="half-width right-marged content-box">
    <div class="shadowed padtable" rules="rows" bordercolor="blue" border="1" frame="void">
        <div class="headrow"><div class="form"><?=CHtml::activeLabel($payform, 'ik_paysystem_alias')?></div></div>
    <?php

    if (!empty($data)) {
        echo CHtml::activeRadioButtonList($payform, 'ik_paysystem_alias', $data, array(
        'template'=>'<div class="trow">{input}{label}</div>',
        'encode'=>false,
        'separator'=>'',
            'name'=>'ik_paysystem_alias'
        ));
        }else
    {
        echo "Нет данных для отображения";
    }
    ?>
    </div>
</div>
<div class="form left">
<?=CHtml::hiddenField('ik_shop_id', $payform->ik_shop_id)?>
<?=CHtml::hiddenField('ik_payment_amount', $payform->ik_payment_amount)?>
<?=CHtml::hiddenField('ik_payment_id', $payform->ik_payment_id)?>
<?=CHtml::hiddenField('ik_payment_desc', $payform->ik_payment_desc)?>
<div class="row buttons">
<?php echo CHtml::submitButton('Перейти к оплате'); ?>
</div>
</div>


<?php if(false) { ?>

        <?php echo CHtml::beginForm(); ?>
<div class="row">
<?php echo CHtml::dropDownList('cashin', '', CHtml::listData($data,'ID','mtID')); ?>
    <?=CHtml::textField('amount')?>
</div>
<div class="action">
<?php echo CHtml::submitButton('Отправить'); ?>
</div>
<?php echo CHtml::endForm(); ?> <?php }?>
</div>