<?php if(isset($_POST['PayoutForm']) && $_POST['PayoutForm']['method']) : ?>
    <?php
    if (sizeof($transits)>0)
    foreach ($transits as $key=>$val) {
        $balance = floor($val['amount'] * 100)/100;
        $data['fx-' . $val['currency']] = "<div class='mtid curr'>FX-{$val['alphaCode']}</div>
              <div class='type'>".Yii::t('payout', "Транзитный счет")."</div>
              <div class='ballance'>$balance</div>";
    }

//die();

    if (sizeof($tradeaccounts)>0)
    foreach ($tradeaccounts as $key=>$val) {

        if (isset($mtdata[$val['mtID']]['leverage'])) {
            $leverage = $mtdata[$val['mtID']]['leverage'];
            $leverageclass='';
        } else {
            $leverage = $val['leverage'];
            $leverageclass='cached';
        };
        if (isset($mtdata[$val['mtID']]['balance'])) {
            $balance = floor($mtdata[$val['mtID']]['balance'] * 100)/100;
            $balanceclass='';
        } else {
            $balance = floor($val['amount'] * 100)/100;
            $balanceclass='cached';
        };

        $data[$val['mtID']] = "<div class='mtid curr'>{$val['mtID']}</div>
              <div class='type'>{$val['name']}</div>
              <div class='$balanceclass ballance'>$balance</div>
              <div class='$leverageclass leverage'>1:$leverage</div>";
//        $data[$val['ID']] = "<b>{$val['mtID']}</b>";
    }
    ?>

<div class="cnt left">
	 <h3><?= Yii::t('payout', 'Вывод средств') ?></h3>
	 
    <div class="separator"></div>
<div class="form">

<form method="POST">
	<input type="hidden" name="PayoutForm[method]" value="<?=$_POST['PayoutForm']['method']?>" />

    <?php


		echo "<div style='height: 10px;'></div>";
		$this->widget('Flashes');


    ?>
    <?=CHtml::beginForm()?>
<div class="half-width right-marged content-box">
    <div class="shadowed padtable" rules="rows" bordercolor="blue" border="1" frame="void">
        <div class="headrow"><div class="form"><?= Yii::t('payout', 'Выберите счет') ?></div></div>
    <?php
    if (!empty($data)) {
        echo CHtml::RadioButtonList("payment", 0, $data, array(
        'template'=>'<div class="trow">{input}{label}</div>',
        'encode'=>false,
        'separator'=>''
        ));
        }else
    {
        echo Yii::t('payout', "Нет счетов для отображения");
    }
    ?>
    </div>
</div>

<label for="payout_amount"><?= Yii::t('payout', 'Сумма') ?></label>
<div class="row">
<input id="payout_amount" type="text" name="payout[payout_amount]" autocomplete="off" size="47">
</div>

<?php if ($showPaymentPass): ?>
    <p>
        <?= Yii::t('payout', 'Пожалуйста, введите платежный пароль для подтверждения платежа. <br>
        Он был создан Вами при первом входе в Личный кабинет.') ?>
    </p>
    <label for="payout_pass"><?= Yii::t('payout', 'Платежный пароль') ?></label>
    <div class="row">
        <input id="payout_pass" type="password" name="payout[payout_pass]" autocomplete="off" size="47">
    </div>
<?php endif; ?>

<div class="row buttons">
   <input type="submit" value="<?= Yii::t('payout', 'Вывести') ?>">
</div>
</form>
</div>
</div>

<script type="text/javascript" >
var curent_price = 9999999;
	$(document).ready(function() {
		$('.trow label').click(function() {
			curent_price = parseFloat($(this).children('.ballance').html().replace(",", "."));
			if($('#payout_amount').val() > curent_price) $('label[for=payout_amount]').css('color', 'red');
			else $('label[for=payout_amount]').css('color', '#C7C7C7');
		});

		$('#payout_amount').keyup(function(){

			if($(this).val() > curent_price) $('label[for=payout_amount]').css('color', 'red');
			else $('label[for=payout_amount]').css('color', '#C7C7C7');
		});
	});
</script>

<?php else : ?>
<div class="cnt left">
    <h3><?= Yii::t('payout', 'Вывод средств') ?></h3>
    <div class="separator"></div>
        <?php

    if(Yii::app()->user->hasFlash('error')) {
			 echo "<div style='height: 10px;'></div>";
		    $this->widget('Flashes');
    	}

    ?>
    <?php

		$out_allow = 0;
    if (sizeof($credentials)>0)
	
    foreach ($credentials as $key=>$val) {
		
			$method = Yii::t('payout', $val['payoutmethods_']['name']);

			  $status = $val['status'];

			  if($status == 1)$out_allow = 1;

			  switch($status) {
					case 0:
						$status_title = Yii::t('payout', "Ожидает аттестации");
						break;
					case 1:
						$status_title = Yii::t('payout', "Аттестован");
						break;
					case 2:
						$status_title = Yii::t('payout', "Не аттестован");
						break;
			  }

			$data[$val->ID] = "<div class='' style='width: 185px;'>$method</div>
				  <div class='mtid curr'>$val->accountnumber</div>
				  <div class='right'>".$status_title."</div>
				  <div class='pad'>".($val->date ? date('d.m.Y', $val->date) : '')."</div>
				  ";
		
    }

    ?>
    <?=CHtml::beginForm()?>
<div class="content-box">
    <div class="shadowed padtable" rules="rows" bordercolor="blue" border="1" frame="void">
        <div class="headrow">
			<div class="form"><?=CHtml::activeLabel($payoutform, 'method', array( 'style'=>'width: 175px;'))?>
			<label for="PayoutForm_method"><?= Yii::t('payout', 'Номер счета') ?></label>
			<label style="margin-left:133px;" for="PayoutForm_method"><?= Yii::t('payout', 'Дата добавления') ?></label>
			<label style="float: right;" for="PayoutForm_method"><?= Yii::t('payout', 'Статус') ?></label></div>
		</div>
    <?php
    if (!empty($data)) {
        echo CHtml::activeRadioButtonList($payoutform, 'method', $data, array(
            'template'=>'<div class="trow">{input}{label}</div>',
            'encode'=>false,
            'separator'=>'',
        ), array('1'=>array('disabled'=>true, 'label'=>'123')));
        }else
    {
        echo Yii::t('payout', "Нет счетов для отображения");
    }
    ?>
    </div>
</div>
<div class="form left">



    <?/*=CHtml::link('Нажмите тут, чтобы добавить платежные реквизиты.', array('default/newcredentials'))*/?>

    <br>
    <div class="row buttons">
    <?php if($out_allow) echo '<input type="submit" value="'.Yii::t('payout',"Вывести").'">'; //echo CHtml::submitButton('Перейти к выбору счета'); ?>
	<input style="margin-left:10px;" type="button" value="<?= Yii::t('payout', 'Добавить платежные реквизиты') ?>" class="redirectbutton greybutton">
	</div>
</div>
</div>
</div>
<?php endif; ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.redirectbutton').click(function(){
			window.location = '/<?=$_GET['language']?>/payout/default/newcredentials';
		});
	});
</script>