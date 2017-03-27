    <?php
    if (sizeof($transits)>0)
    foreach ($transits as $key=>$val) {
        $balance = floor($val['amount'] * 100)/100;
        $data['fx-' . $val->currency] = "<div class='mtid curr'>FX-{$val->currency_['alphaCode']}</div>
              <div class='type'>Транзитный счет</div>
              <div class='ballance'>$balance</div>";
//        $data[$val['ID']] = "<b>{$val['mtID']}</b>";
    }
//    $data[$transitID] = "<div class='mtid curr'>$transitID</div>
//                       <div class='type'>Транзитный счет</div>
//                       <div class='ballance'>" . round ($transit,2) . "</div>
//    ";
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
        //if($val->fxType_['name'] == 'FXCent') $balance *= 100;
        $data[$val['mtID']] = "<div class='mtid curr'>{$val['mtID']}</div>
              <div class='type'>{$val->fxType_['name']}</div>
              <div class='$balanceclass ballance'>$balance</div>
              <div class='$leverageclass leverage'>1:$leverage</div>";
    }
    ?>
<div class="cnt left">
    <h3>Пополнение счета</h3>
    <?=CHtml::beginForm()?>
<div class="half-width right-marged content-box">
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
        echo "Нет счетов для отображения";
    }
    ?>
    </div>
</div>
<div class="form left">
    <br/><?=CHtml::activeLabel($payform, 'ik_payment_amount')?>
<div class="row">
    <?=CHtml::activeTextField($payform, 'ik_payment_amount', array('size'=>'47', 'autocomplete'=>'off'))?>
</div>
<div class="row buttons">
<?php echo CHtml::submitButton('Перейти к выбору способа оплаты');  ?>
</div>
</div>
</div>