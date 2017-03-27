<?php
Yii::app()->clientScript->registerScript('inend1',"
$('#TransferForm_source :radio, #TransferForm_target :radio').live('click',function(e){
    if($(this).hasClass('on')){
       $(this).removeAttr('checked');
    }
    $(this).toggleClass('on');
}).filter(':checked').addClass('on');

",  CClientScript::POS_END);
?>
<script type="text/javascript">
//    $('#TransferForm_source :radio:checked').live('click',function(){
//       $('#TransferForm_source :radio:checked').prop('checked',false);
//    });
</script>

<div class="cnt left">
    <h3><?= Yii::t('account', 'Внутренний перевод') ?></h3>
    <div class="separator full-center"></div>
    <div>
        <?php echo Yii::t('transfer', 'Выберите счет, с которого желаете сделать перевод (Исходный счет) и счет на который желаете перевести средства (Целевой счет)') ?>
    </div>
    <?php
    $data[$transitID] = "<div class='mtid curr'>$transitID</div>
                       <div class='type'>".Yii::t('account', 'Транзитный счет')."</div>
                       <div class='ballance'>" . floor($transit * 100)/100 . "</div>
    ";
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
              <div class='type'>{$val->fxType_['name']}</div>
              <div class='$balanceclass ballance'>$balance</div>
              <div class='$leverageclass leverage'>1:$leverage</div>";
//        $data[$val['ID']] = "<b>{$val['mtID']}</b>";
    }
    ?>
<?=CHtml::beginForm()?>
<div class="half-width left right-marged content-box">
    <div class="shadowed padtable" rules="rows" bordercolor="blue" border="1" frame="void">
        <div class="headrow"><div class="form"><?=CHtml::activeLabel($transfer, 'source')?></div></div>
    <?php
    if (!empty($data)) {
        echo CHtml::activeRadioButtonList($transfer, 'source', $data, array(
        'template'=>'<div class="trow">{input}{label}</div>',
        'encode'=>false,
        'separator'=>''
        ));
        }else
    {
        echo Yii::t('account', "Нет счетов для отображения");
    }
    ?>
    </div>
</div>
<div class="half-width left content-box">
    <div class="shadowed padtable half-width left" rules="rows" bordercolor="blue" border="1" frame="void">
        <div class="headrow"><div class="form"><?=CHtml::activeLabel($transfer, 'target')?></div></div>
    <?php
    if (!empty($data)) {
        echo CHtml::activeRadioButtonList($transfer, 'target', $data, array(
        'template'=>'<div class="trow">{input}{label}</div>',
        'encode'=>false,
        'separator'=>''
        ));
    } else
    {
        Yii::t('account', "Нет счетов для отображения");
    }
?>
    </div>
</div>
<div class="form left">
    <br/><?=CHtml::activeLabel($transfer, 'amount')?>
<div class="row">
    <?=CHtml::activeTextField($transfer, 'amount')?>
</div>
<?$this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
<?=CHtml::activeLabel($transfer, 'verifyCode',array("class"=>"label-captcha label-left"))?>
<div class="captcha row">
    
    <?=CHtml::activeTextField($transfer, 'verifyCode',array("class"=>"captcha-input input-full","autocomplete"=>"off"))?>
    <?=CHtml::error($transfer,'verifyCode',array('class'=>'line-error ')); ?>
</div>
<div class="row buttons">
<?php echo CHtml::submitButton(Yii::t('messages', 'Отправить')); ?>
</div>
</div>
<?php echo CHtml::endForm(); ?>
</div>
