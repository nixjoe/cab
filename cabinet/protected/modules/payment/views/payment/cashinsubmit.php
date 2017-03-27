<?php
    if (sizeof($paymethods)>0)
    foreach ($paymethods as $item) {
        $data[$item['config_name']] = '<div>'.$item['name'].'</div>';
    }
    ?>
<div class="cnt left">
    <h3><?= Yii::t('payment', 'Пополнение счета') ?></h3>
    <div class="separator full-center"></div>

<div class="half-width right-marged content-box">
    <div class="shadowed padtable" rules="rows" bordercolor="blue" border="1" frame="void">
        <div class="headrow"><div class="form"><?=CHtml::activeLabel($payform, 'ik_paysystem_alias')?></div></div>
    <?php

    if (!empty($data)) {
        /*echo CHtml::activeRadioButtonList($payform, 'ik_paysystem_alias', $data, array(
        'template'=>'<div class="trow">{input}{label}</div>',
        'encode'=>false,
        'separator'=>'',
            'name'=>'ik_paysystem_alias'
        ));*/
			echo '<span id = "ik_paysystem_alias"><input type="hidden" name="ik_paysystem_alias" value="" id="ytik_paysystem_alias">';
			foreach ($paymethods as $k => $itm){?>
				<div class="trow" rel="<?=$itm['id']?>">
				<input type="radio" name="ik_paysystem_alias" value="<?=$itm['config_name']?>" id="ik_paysystem_alias_<?=$k?>">
				<label for="ik_paysystem_alias_<?=$k?>"><div><?=Yii::t('paysystems', $itm['name'])?></div></label>
				</div>
			<?}
			echo '</span>';
        }else {
        echo Yii::t('payment', "Нет данных для отображения");
    }
    ?>
    </div>
</div>

<div class="form left">

<div class="row buttons">
<form id="payment_form" action="" method="POST">

<?php echo CHtml::submitButton(Yii::t('payment', 'Перейти к оплате'), array('name'=>false)); ?>
</form>
</div>
</div>

</div>

<script type="text/javascript">
$('document').ready(function(){
    $('#ik_paysystem_alias .trow:first input:first').addClass('on');
	$('#ik_paysystem_alias .trow').click(function(e) {
            /*if ($(e.target).is('input')) {
                return true;
            }*/

			var _data = 'paymentId=<?=$payment->id?>';
				_data+= '&methodName=' + $(this).children('input').val();
				_data+= '&itemID=' + $(this).attr('rel');

			$.ajax({
				url: '/payment/payment/getform?language=<?=$_GET['language']?>',
				type: 'POST',
				data: _data,
				dataType: 'json',
				timeout: 2000,
				error:function (XMLHttpRequest, textStatus, errorThrown) {
					//alert(textStatus);
				},
				beforeSend: function(){

				},
				success: function(response){
					
					$('#payment_form input[type=hidden]').remove();
					$('#payment_form').attr('action', response.submit_url);
					$.each(response.fields, function(key, val) {
						$('#payment_form').append('<input type="hidden" name="'+key+'" value="'+val+'" />');
					});
				}
			});
	});
});
</script>