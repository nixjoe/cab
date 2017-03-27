<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);
    Yii::app()->clientScript->registerScript('end',"
    $('#converter-toggler').click(function(){
        $('#converter').slideToggle('fast','swing', function(){
            $('#converter-toggler').toggleClass('toggler-expanded');
        });
    });
    window._docAsk = null;
    window._sCurSel = null;
    _getDocRate = function() {
        _sCurSel = $('#cursel :radio:checked+label div.val');
        window._docAsk = parseFloat(_sCurSel.attr('data-ask'));
        return (_sCurSel.attr('data-rate')||_sCurSel.text()||'');
    }
    _cConvert = function(give, rev) {
        if (window._docAsk) {
            return rev ? Math.round(give*_docAsk*100)/100 : Math.round(give/_docAsk*100)/100;
        }
        return rev ? Math.round(give / document.rate * 100)/100 : Math.round(give * document.rate * 100)/100;
    }
    $('#cursel :radio').live('change', function(){
        document.rate = _getDocRate();
        f = ($('#cursel :radio:checked').val().substr(0,3));
        t = ($('#cursel :radio:checked').val().substr(3,3));
        $('#curfrom :radio[value='+f+']').attr('checked','checked');
        $('#curto :radio[value='+t+']').attr('checked','checked');
        $('#curto :radio, #curfrom :radio').change();
        give = $('#ConvertForm_give').val();
        $('#ConvertForm_get').val(_cConvert(give));
    });
    $('input[name=\"ConvertForm[from]\"], input[name=\"ConvertForm[to]\"]').live('change', function(){
        $('#cursel :radio:checked').prop('checked',false);
        f = $('#curfrom :radio:checked+label').text();
        t = $('#curto :radio:checked+label').text();
        //if ((f <> t) && (typeof()))
        //alert(typeor($('#cursel :radio[value='+f+t+']')));
        $('#cursel :radio[value='+f+t+']').prop('checked',true);
        r = _getDocRate();
        if (r.length)
            document.rate = r
        else
            if(f == t)
                document.rate = 1;
            else
                document.rate = 0;
        give = $('#ConvertForm_give').val();
        $('#ConvertForm_get').val(_cConvert(give));
    });
    $('#cursel :radio:first').prop('checked',true);
    $('#cursel :radio').change();" .
        //Отобразим конвертер, если небыло произведено конвертирование валют - скроем конвертер
        (($conv)?"":"$('#converter').hide();")
	."

    $('#ConvertForm_get').val('');
",  CClientScript::POS_READY); ?>
<script type="text/javascript">
	setInterval( function() {
			$('#converter .padtable').load('/account/index?ajax=true');
	}, 5000);
    $('#ConvertForm_give').live('keyup',function(){
        give = $('#ConvertForm_give').val();
        $('#ConvertForm_get').val(_cConvert(give));
    });
    $('#ConvertForm_get').live('keyup',function(){
        give = $('#ConvertForm_get').val();
        $('#ConvertForm_give').val(_cConvert(give, true));
    });
    $('#equals').live('click',function(){
        f = $('#ConvertForm_from :selected').val();
        t = $('#ConvertForm_to :selected').val();
        $('#cursel :radio[value='+t+f+']').prop('checked',true);
        $('#cursel :radio').change();
    });
    $('.select-container .radiobox').hide();
</script>

<div class="cnt left">
    <h3><?= Yii::t('account', 'Ваш кошелек FXPRIVATE') ?>: <span>FX<?php echo $transitid; ?></span></h3>
    <div  class="content-box">
        <?php  if(!Yii::app()->user->hasFlash('notice acc')) $this->widget('Flashes'); ?>
        <?php foreach ($transits as $key=>$val) {
        echo "<div class='transits'>";
        echo "<div class='curr left'>{$val->currency_['alphaCode']}:</div>";
        echo "<div class='val right'>" . (floor($val['amount'] * 100)/100) . "</div>";
        echo "</div>";
    }    
?>        
    </div>
    <div class="separator full-center" <?/*=((sizeof($tradeacc) == 0)? "style='display:none;'" : '')*/?>></div>
    <div id="converter-toggler" class="toggler" <?/*=((sizeof($tradeacc) == 0)? "style='display:none;'" : '')*/?>><h3><?= Yii::t('account', 'Конвертер валют') ?></h3></div>
    <div id="converter" class="content-box toggler-content">
        <?=CHtml::beginForm()?>
        <div class="shadowed padtable curselector left">
            <?php
                $data = array();
				foreach ($rates as $k=>$v) {
                    if ($k == 'IDRUSD' && isset($ask[$k])) {
                        $data[$k]= '
								<div class="curpair curr left">'.$k.'</div>
								<div class="val right" data-ask="'.(@$ask[$k]).'" data-rate="'.$v.'">'.$ask[$k].'</div>
							';
                        continue;
                    }
                    if($k != 'EURRUB' && $k != 'RUBEUR') {
                        $data[$k]= '
								<div class="curpair curr left">'.$k.'</div>
								<div class="val right" data-ask="'.(@$ask[$k]).'" data-rate="'.$v.'">'.$v.'</div>
							';
                    }
                }
                $datadropdown=$curlist;

            ?>
            <?=CHtml::radioButtonList("cursel", '', $data, array(
                'separator'=>'',
                'template'=>'<div class="trow">{input}{label}</div>',
                'encode'=>false
                ))?>
        </div>
        <div class="left">
            <div class="form">
                <div class="row">
					<?//unset($datadropdown['RUB']);?>
                    <?=CHtml::activeTextField($convert, 'give', array('autocomplete'=>'off'))?>
					<div class="select-container rel-container" id="curfrom">
						<div class="valuebox"><p></p>
						</div>
						<div class="radiobox">
							<div class="optcontainer">
							<?=CHtml::activeRadioButtonList($convert, 'from', $datadropdown, array(
								'class'=>'overlayed',
								'separator'=>''
								))?>
							</div>
						</div>
					</div>
                    <?php CHtml::activeDropDownList($convert, 'from', $datadropdown)?>
                    <div id="equals"></div>
                    <?=CHtml::activeTextField($convert, 'get', array('autocomplete'=>'off'))?>
					<div class="select-container rel-container" id="curto">
						<div class="valuebox"><p></p>
						</div>
						<div class="radiobox">
							<div class="optcontainer">
							<?=CHtml::activeRadioButtonList($convert, 'to', $datadropdown, array(
								'class'=>'overlayed',
								'separator'=>''
								))?>
							</div>
						</div>
					</div>
                    <?php CHtml::activeDropDownList($convert, 'to', $datadropdown)?>
                </div>
                <div class="row buttons">
                    <div id="converter-submit">
                        <?=CHtml::submitButton(Yii::t('account', 'Конвертировать'), array('class'=>'greybutton'))?>
                    </div>
                </div>
            </div>
        </div>
    <?=CHtml::endForm()?>
    </div>
    <div class="separator full-center"></div>
    <h3><?= Yii::t('account', 'Счета') ?></h3>
    <?php 
    
    if(Yii::app()->user->hasFlash('notice acc')) {
			 echo "<div style='height: 10px;'></div>";    	
		    $this->widget('Flashes');	
    	}
    
    ?>
<?php if (sizeof($tradeacc) == 0)
    {
        echo Yii::t('account', "В данный момент у Вас нет активных счетов").". " . CHTml::link(Yii::t('account', 'Нажмите тут, чтобы открыть новый счет'), array('/account/new'));

    }


    else {?>
<table class="shadowed padtable content-box" rules="rows" bordercolor="blue" border="1" frame="void">
    <tr class="headrow">
        <th>
            <?= Yii::t('account', 'Номер счета') ?>
        </th>
        <th>
            <?= Yii::t('account', 'Тип счета') ?>
        </th>
        <th>
            <?= Yii::t('account', 'Баланс') ?>
        </th>
        <th>
            <?= Yii::t('account', 'Плечо') ?>
        </th>
        <th width="125">
            <?= Yii::t('account', 'Изменить плечо') ?>
        </th>


    </tr>
    <?php foreach ($tradeacc as $key=>$val) {
    ?>
<tr class="trow">
    <?php
        echo "  <td class='curr'>
                {$val['mtID']}
                </td>
                <td>
                {$val->fxType_['name']}
                </td>";
        if (isset($mtdata[$val['mtID']]['leverage'])) {
            $leverage = $mtdata[$val['mtID']]['leverage'];
            $leverageclass='';
        } else {
            $leverage = $val['leverage'];
            $leverageclass='cached';
        };
        if (isset($mtdata[$val['mtID']]['balance'])) {
            $balance = round ($mtdata[$val['mtID']]['balance'],2);
            $balanceclass='';
        } else {
            $balance = round ($val['amount'],2);
            $balanceclass='cached';
        };
			//if($val->fxType_['name'] == 'FXCent') $balance *= 100;
        echo "
            <td class=$balanceclass>
            $balance " .
            CHtml::link(" ", array("/payout/"), array('class'=>'padminus right')).
            CHtml::link(" ", array("/payment/payment/", 'target' => $val['mtID']), array('class'=>'padplus right')).
                "
            </td>
            <td class=$leverageclass>
            1:$leverage
            </td>
            <td>".
        CHtml::link(Yii::t('account', "Изменить плечо"), $this->createUrl("/account/edit", array('id'=>$val['ID'])), array('class'=>'padgreybutton'))
            ."</td>
        ";
    ?>
</tr>
    <?php
}
?>
</table>
<? } ?>
    <div class="separator full-center"></div>
</div>