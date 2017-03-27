<?php
	$cs=Yii::app()->getClientScript();
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.fancybox.js', CClientScript::POS_END);

	$cs->registerScript('.user_msg',"
		jQuery(document).ready(function(){
			$('a#a_user_msg,a#lvg_link, a#add_trans_link,a#remove_trans_link,a#add_trade_link,a#remove_trade_link').fancybox({
				'hideOnContentClick': true
			});

			$('.sel_atest').change(function(){
				var id = $(this).attr('id').substring(10);
				if($(this).val() == 1)
					$.ajax({
						dataType: 'json',
						method: 'POST',
						data: {'id' : id, 'action' : 'checkVerify'},
						success: function(data) {
							if(data.result) {
								if(confirm('Счет уже аттестован кошелек FXPRIVATE '+data.purse+'. Аттестовать?')) {
									$('#sel_atest_'+id+' option[value=1]').attr('selected', 'selected');
								} else {
									$('#sel_atest_'+id+' option[value=0]').attr('selected', 'selected');
								}
							}
						},
					});
			});

			$('#setNewPass').click(function() {
				if(confirm('Вы уверены что хотите установить новый пароль для пользователя ".$model->givenName."?')) {
					$.ajax({
						url: '',
						type: 'POST',
						data: 'newpass='+$('input[name=newPass]').val()+'&act=np',
						success: function() {
								alert('Новый пароль установлен.');							
							}
					});
				} else {
					$(this).prop('disabled', true);
					$('input[name=newPass]').val('Пароль');
				}				
				return false;
			});

			$('.cansel_atest').click(function(){
				if(confirm('Вы уверены?')) $(this).parent().parent().parent().parent('').parent('').css('display','none').next().css('display', 'block').find('.sel_atest').val(2);
				return false;
			});

			$('.verify').change(function(){
				if($(this).val() == 'nofix') {
					$('#reason_'+$(this).attr('id')).show();
				} else $('#reason_'+$(this).attr('id')).hide();
			});

			$('.zayavki').click(function(){
				if($(this).css('height') == '16px') {
					$('.zayavki').css('height', '16px');
					$(this).css('height', 'auto');
				} else {
					$(this).css('height', '16px');
				}

			});

			$('select[name=status_payout]').change(function(){
				var val = $(this).val();
				if(val != '1') {
					if(confirm('Вы уверены?')) {
						$.ajax({
							method: 'POST',
							data: {'status' : val, 'transferID' : $(this).attr('rel'), 'action' : 'setStatus', },
							success: function() {
								$(this).parent().parent().remove();
							}
						});
					} else {
						$(this).val(1);
					}
				}
			});
		});
		");
?>

<script>
		$(document).ready(function(){
			$('.span-19').removeClass('span-19');
			$('select.docs').change(function(){
				if (this.value == 'nofix'){
					var value = 2;
				}else if (this.value == 'fix'){
					var value = 1;
				}
				$(this).next('input').val(value);
			});
			

		});
		function transAdd(id) {
			$('#add_trans_id').val(id);
			$('#add_trans_link').trigger('click');
			return false;
		}

		function transRemove(id) {
			$('#remove_trans_id').val(id);
			$('#remove_trans_link').trigger('click');
			return false;
		}
		function tradeAdd(id) {
			$('#add_trade_id').val(id);
			$('#add_trade_link').trigger('click');
			return false;
		}

		function tradeRemove(id) {
			$('#remove_trade_id').val(id);
			$('#remove_trade_link').trigger('click');
			return false;
		}

		function changeLeverage(id,fxtype){
			$.ajax({
				dataType: 'json',
				method: 'POST',
				data: {'fxtype' : fxtype, 'action' : 'getLeaverages'},
				success: function(data) {
					if(data) {
						var lvg = data.leaverages
						$('#l_sel').html('');
						for(lvg_val in lvg) {
							$('#l_sel').append("<option>"+lvg[lvg_val]+"</option>");
						}
					}
				}
			});
			$('#lvg_link').trigger('click');
		}
</script>

<style>
#user_msg{
	width:700px;

}

#user_msg input, #user_msg select, #user_msg textarea{
	width:300px;
}
#user_msg textarea{
	height: 300px;
}
.transits {
    background: none repeat scroll 0 0 #80CFFF;
    border-radius: 5px 5px 5px 5px;
    display: inline-block;
    height: 16px;
    margin: 0 12px 12px 0;
    padding: 8px;
    width: 112px;
}

.curr, .val {
    font-family: arial,sans-serif;
    font-size: 14px;
    font-weight: bold;
}
.val {
    color: #FFFFFF;
}

.tti {
	font-weight: bold; text-decoration: underline;
}

.right {
    float: right !important;
}

.sumsub {
	margin-top: 10px;
	width: 100px;
}
</style>
<div style='display: none;' class='container'>

	<a href='#add_sum' id='add_trans_link'></a>
	<form method='POST' id='add_sum'>
		<p> Введите сумму, на которую<br> Вы хотите пополнить счет.</p>
		Сумма: <input name='trans_plus[sum]'><br>
		<input type='hidden' id='add_trans_id' name='trans_plus[id]'>
		<input type='submit' class='sumsub' value='Внести'>
	</form>
	<a href='#remove_sum' id='remove_trans_link'></a>
	<form method='POST' id='remove_sum'>
		<p> Введите сумму, которую<br> Вы хотите снять со счета.</p>
		Сумма: <input name='trans_minus[sum]'><br>
		<input type='hidden' id='remove_trans_id' name='trans_minus[id]'>
		<input type='submit' class='sumsub' value='Вывести'>
	</form>

	<a href='#add_sum_t' id='add_trade_link'></a>
	<form method='POST' id='add_sum_t'>
		<p> Введите сумму, на которую<br> Вы хотите пополнить счет.</p>
		Сумма: <input name='trade_plus[sum]'><br>
		<input type='hidden' id='add_trade_id' name='trade_plus[id]'>
		<input type='submit' class='sumsub' value='Внести'>
	</form>
	<a href='#remove_sum_t' id='remove_trade_link'></a>
	<form method='POST' id='remove_sum_t'>
		<p> Введите сумму, которую<br> Вы хотите снять со счета.</p>
		Сумма: <input name='trade_minus[sum]'><br>
		<input type='hidden' id='remove_trade_id' name='trade_minus[id]'>
		<input type='submit' class='sumsub' value='Вывести'>
	</form>
	<a href='#leverage_change' id='lvg_link'></a>
	<form method='POST' id='leverage_change'>
		<select name='leverage' id='l_sel'>

		</select>
		<input type='submit' value='Изменить плечо'>
	</form>

	<form method='POST' id='user_msg'>
		<table>
			<tr>
				<td>
					Тема сообщения:
				</td>
				<td>
					<input name='send_msg[subject]' type='text'>
				</td>
			</tr>
			<tr>
				<td>
					От кого:
				</td>
				<td>
					<select name='send_msg[from]'>
					<?php
						foreach($managers as $man) :
					?>
						<option value='<?= $man['userID'] ?>'> <?= $man['position_name'] ?> </option>
					<?php
						endforeach;
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Сообщение:</td>
				<td><textarea name='send_msg[msg]'></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td><input type='submit' value='Отправить'></td>
			</tr>
		</table>
	</form>
</div>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pages-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

		<table>
			<tr>
				<td><?php echo $form->labelEx($model,'familyName'); ?></td>
				<td><?php echo $form->labelEx($model,'givenName'); ?></td>
				<td><?php echo $form->labelEx($model,'middleName'); ?></td>
			</tr>
			<tr>
				<td>
					<?php echo $form->textField($model,'familyName',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'familyName'); ?>
				</td>
				<td>
					<?php echo $form->textField($model,'givenName',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'givenName'); ?>
				</td>
				<td>
					<?php echo $form->textField($model,'middleName',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'middleName'); ?>
				</td>
				
			</tr>

			<tr>
				<td><?php echo $form->labelEx($model,'country'); ?></td>
				<td><?php echo $form->labelEx($model,'city'); ?></td>
				<td><?php echo $form->labelEx($model,'phone'); ?></td>
			</tr>
			<tr>
				<td>
					<?php echo $form->textField($model->country_,'rus',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'country'); ?>
				</td>
				<td>
					<?php echo $form->textField($model,'city',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'city'); ?>
				</td>
				<td>
					<?php echo $form->textField($model,'phone',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'phone'); ?>
				</td>
			</tr>

			<tr>
				<td><?php echo $form->labelEx($model,'email'); ?></td>
				<td><?php echo $form->labelEx($model,'birthDate'); ?></td>
                <td><?php echo $form->labelEx($model,'regdate'); ?></td>
			</tr>
			<tr>
				<td>
					<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'email'); ?>
				</td>
                <td>
                    <?php	$model->birthDate = date("d.m.Y", strtotime($model->birthDate));
                    echo $form->textField($model,'birthDate',array('size'=>60,'maxlength'=>150)); ?>
                    <?php echo $form->error($model,'birthDate'); ?>
                </td>
				<td>
					<?php	$model->regdate = date("d.m.Y", $model->regdate);
						echo $form->textField($model,'regdate',array('size'=>60,'maxlength'=>150)); ?>
					<?php echo $form->error($model,'regdate'); ?>
				</td>
			</tr>
            <tr>
                <td><?php echo $form->labelEx($model,'transitID'); ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
			<tr>
                <td>
                    <?php echo $form->textField($model,'transitID',array('size'=>60,'maxlength'=>150)); ?>
                    <?php echo $form->error($model,'transitID'); ?>
                </td>
				<td><input type="text" value="Пароль" name="newPass" onFocus="if($(this).val() == 'Пароль')$(this).val('')" onBlur="if($(this).val() == '')$(this).val('Пароль')" onkeyup="if($(this).val() != '' && $(this).val() != 'Пароль') { $('#setNewPass').prop('disabled', false) } else { $('#setNewPass').prop('disabled', true) }"/></td>
				<td><button id='setNewPass' disabled="disabled">Установить новый пароль</button></td>
			</tr>
			<tr>
				<td colspan="2">
                    <b>Платёжный пароль</b> - <?=$model->paymentPassword?>
                    <label>
                        <?php echo $form->checkBox($model,'ignorePaymentPass'); ?>
                        Не проверять платежный пароль
                    </label>
                </td>
				<td style='text-align: right'>
					<a href='#user_msg' id='a_user_msg'>Отправить клиенту сообщение</a>
				</td>
			</tr>


	<?if ($model->partner != 0){?>
        <tr>
            <td>
            <?php echo $form->labelEx($model,'partner'); ?>
            <?
            $partnerarr = array('1' => "Ожидает партнёрства", '2' => "Партнёр" );

            ?>
            <?php echo $form->dropDownList($model,'partner',$partnerarr); ?>
            </td>
            <?/*<select name='Users[partner]' >
                    <option <?= ($model->partner == 1 ?"selected='selected'":"") ?> value='1'>Ожидает партнёрства</option>
                    <option <?= ($model->partner == 2?"selected='selected'":"") ?> value='2'>Партнёр</option>
            </select>*/?>
        </tr>
        <tr>
            <td>
                <?php echo $form->labelEx($model,'replenish_client'); ?>
                <?php echo $form->dropDownList($model,'replenish_client', array('1'=>'ДА','0'=>'НЕТ')); ?>
            </td>
        </tr>
	<?}?>

			<tr>
			<td colspan="3">
					<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
				</td>
			</tr>
		</table>


	<?php $this->endWidget(); ?>
	
	
	
	
	<?php	$cntr_oo = 0;
	foreach($userdoc as $userdoc_item) : ?>
	<form method="post" action="/chang_area_private_cab.php?r=users/update&id=<?=$_GET['id']?>">
		<div class="row">
			<table>
				<tr>
					<td>Документ аттестации пользователя</td>
					<td>Серия и номер документа</td>
					<td>Орган, который выдал документ</td>
				</tr>
				<tr>
					<td><input name='docs[<?= $userdoc_item['ID'] ?>][docname]' value='<?= $userdoc_item['docname'] ?>'></td>
					<td><input name='docs[<?= $userdoc_item['ID'] ?>][docnumber]' value='<?= $userdoc_item['docnumber'] ?>'></td>
					<td><input name='docs[<?= $userdoc_item['ID'] ?>][docissuer]' value='<?= $userdoc_item['docissuer'] ?>'></td>
				</tr>
				
				<tr>
					<td>
						<?if ($userdoc_item['status'] == 1){?>
								Аттестован
								<img style='height: 20px' src='/images/Crystal_Project_success.png'></td>
								<input type="hidden" name="docs[<?= $userdoc_item['ID'] ?>][status]" value="1" />
						<?}else{?>
						<?// if(!$cntr_oo++) : ?>
						<select class="docs verify" name='verify[]' id='<?= $userdoc_item['ID'] ?>'>
							<?php if($userdoc_item['status'] == 0) : ?><option>Ожидает аттестации</option><?php endif; ?>
							<option <?= ($userdoc_item['status'] == 2?"selected='selected'":null) ?> value='nofix'>Не аттестован</option>
							<option <?= ($userdoc_item['status'] == 1?"selected='selected'":null) ?> value='fix'>Аттестован</option>
						</select>
						<input type="hidden" name="docs[<?= $userdoc_item['ID'] ?>][status]" value="<?=$model->status?>" />
						<textarea name='reason' id='reason_<?= $userdoc_item['ID'] ?>' style='display: none;'>В атестации личных данных отказано, причина отказа</textarea>
						<input type='submit' value='save'>
						<?}?>
						<?// endif; ?>
					</td>
					<?if (is_array($userdoc_item['doc'])){?>
					
					<td colspan='2'>
						<table>
							<?foreach($userdoc_item['doc'] as $itemdoc){?>
								<tr>
									<td>Аттестационные документы:</td>
									<td><?= $itemdoc['filename'] ?></td>
									<td><a target="_blank" href='<?= Yii::app()->controller->createUrl('Users/ViewImg', array('hash'=>$itemdoc['hash'], 'filename' => $itemdoc['filename'])) ?>'>Показать</a></td>
									<td><a target="_blank" href='<?= Yii::app()->controller->createUrl('Users/LoadImg', array('hash'=>$itemdoc['hash'], 'filename' => $itemdoc['filename'])) ?>'>Загрузить</a></td>
								</tr>
							<?}?>
						</table>
					</td>
					<?}?>
				</tr>
			</table>
		</div>
	</form>
	<?php endforeach ?>
<form method="post" action="/chang_area_private_cab.php?r=users/update&id=<?=$_GET['id']?>">
	<h3>Редактирование вывода средств пользователя:</h3>
	<table width="100%">
		<tr>
			<td style="vertical-align:top;">
				<table>
					<tr>
						<td>
							<span class='tti'>Кошелек FXPRIVATE</span>: <span><?php echo $transitid; ?></span>
						</td>
					</tr>
					<tr>
						<td>
							<div  class="content-box">
							<?php foreach ($transits as $key=>$val) {
								echo "<div class='transits'>";
								echo "<div class='curr left'>{$val->currency_['alphaCode']}:</div>";
								echo "<div class='val right'>" . (floor($val['amount'] * 100)/100) . "</div>";
								echo "<a href='#' onClick='transRemove(".$val['ID'].");return false;' style='color: red; margin: 5px 5px 5px 0;'>Вывести</a>";
								echo "<a href='#' onClick='transAdd(".$val['ID'].");return false;' style='color: green; margin: 5px 0 5px 0;'>Внести</a>";
								echo "</div>";
							    }    ?>
							</div>
						</td>
					</tr>
					<tr>
						<td>

							<span class='tti'>Торговые счета</span>
						</td>
					</tr>
					<tr>
						<td>
							<table class="shadowed padtable content-box" rules="rows" bordercolor="blue" border="1" style="border-radius: 5px 5px 5px 5px; background-color:#80CFFF;" >
								<tr class="headrow">
									<th>
									    Номер счета
									</th>
									<th>
									    Тип счета
									</th>
									<th>
									    Баланс
									</th>
									<th></th>
									<th>
									    Плечо
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
									    CHtml::link(" ", "", array('class'=>'padminus right')).
									    CHtml::link(" ", array("/payment/payment/?target=".$val['mtID']), array('class'=>'padplus right')).
										"
									    </td>
									    <td>
										<a href='#' onClick='tradeRemove(".$val['mtID'].");return false;' style='color: red; margin: 5px 0 5px 0; font-size:23px; font-weight: bold;'>-</a>
										<a href='#' onClick='tradeAdd(".$val['mtID'].");return false;' style='color: green; margin: 5px 0 5px 0; font-size:23px; font-weight: bold;'>+</a>
									    </td>
									    <td class=$leverageclass>
									    <a href='#leverage_change' onClick=\"changeLeverage(".$val['mtID'].",'".$val['fxType']."');return false;\" id='lv_ch'>1:$leverage</a>
									    </td>

									";
								    ?>
								</tr>
								    <?php
								}
								?>
							</table>

						</td>
					</tr>
				</table>
			</td>
			<td style="vertical-align:top;">
				<table>
					<tr>
						<td  class='tti'> Доступные способы вывода </td>
					</tr>
					<tr>
						<td>
							<table>
                                <?foreach ($payoutcredentials as $items){ ?>
                                    <tr>
                                        <td>
                                            <div class='atest' <?= $items['status'] != 1 ? 'style="display:none"' : null?>>
                                                <table>
                                                    <tr>
                                                        <td><?=$items['pmname']?>  (<?=$items['accountnumber']?>)</td>
                                                        <td><img style='height: 20px' src='/images/Crystal_Project_success.png'></td>
                                                        <td><a href='#' class='cansel_atest' style='color: red'>Отменить</a></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class='not_atest' <?= $items['status'] == 1 ? 'style="display:none"' : null?>>
                                                <label> <?=$items['pmname']?>  (<?=$items['accountnumber']?>)</label>
                                                <select name="pm[<?=$items['ID']?>]" class='sel_atest' id="sel_atest_<?= $items['ID'] ?>">
                                                            <option <?= $items['status'] == 0 ? 'selected="selected"' : ''?> value="0">Ожидает аттестации</option>
                                                            <option <?= $items['status'] == 1 ? 'selected="selected"' : ''?> value="1">Аттестован</option>
                                                            <option <?= $items['status'] == 2 ? 'selected="selected"' : ''?> value="2">Не аттестован</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                <? } ?>
							</table>
						</td>
					</tr>
                    <?/*
					<tr>
						<td  class='tti'> Заявки пользователя на вывод средств </td>
					</tr>
					<tr>
						<td>
							<table>
								<tr>
									<td style='color: #80CFFF; font-size: 13px;'>Дата</td>
									<td style='color: #80CFFF; font-size: 13px;'>| Содержание заявки</td>
								</tr>
								<?php foreach($payouts as $pos): ?>
								<tr>
									<td style='font-size: 13px;'><?= $pos->date ?></td>
									<td class='zayavki' style='font-size: 13px; display: block; height:16px; text-overflow: ellipsis; overflow: hidden;'>
										<?= $pos->add_info ?> <br>
										<select name='status_payout' rel='<?= $pos->ID ?>'>
											<option <?= $pos->status == 1 ? 'selected="selected"' : ''  ?> value='1'>Принята</option>
											<option <?= $pos->status == 0 ? 'selected="selected"' : ''  ?> value='0'>Исполнена</option>
											<option <?= $pos->status == 2 ? 'selected="selected"' : ''  ?> value='2'>Отклонена</option>
                                            <option <?= $pos->status == 5 ? 'selected="selected"' : ''  ?> value='5'>Отменена клиентом</option>
										</select>
									</td>
								</tr>
								<?php endforeach; ?>
							</table>
						</td>
					</tr>
                    */?>
				</table>
			</td>
		</tr>

	</table>
    <?php
    $data = array_map(function($x){
        return $x->getAttributes();
    }, $payouts);

    $dataProvider = new CArrayDataProvider($data, array(
        'keyField'   => 'ID',
        'pagination'   => false
    ) );
    function show_select($id, $status){
        return "<select name='status_payout' rel='".$id."'>
            <option ".($status == 1 ? 'selected=\"selected\"' : ''  )." value='1'>Принята</option>   
            <option ".($status == 0 ? 'selected=\"selected\"' : ''  )." value='0'>Исполнена</option>
            <option ".($status == 2 ? 'selected=\"selected\"' : ''  )." value='2'>Отклонена</option>
            <option ".($status == 5 ? 'selected=\"selected\"' : ''  )." value='5'>Отменена клиентом</option>
        </select>";
    }
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'payouts-grid',
        'dataProvider'=>$dataProvider,
        'columns'=>array(
            array(
                'name' => 'date',
                'header' => 'Дата'
            ),
            array(
                'name' => 'amount',
                'header' => 'Сумма',
                'value'=>'round($data["amount"], 2)'
            ),
            array(
                'name' => 'add_info',
                'header' => 'Содержание заявки',
                'type'=>'raw'
            ),
            array(
                'name' => 'status',
                'header' => 'Статус',
                'type'=>'raw',
                'value' => 'show_select($data["ID"], $data["status"])'
            )
        ),
        'enableSorting' => false,
        'enablePagination' => false,
        'summaryText' => 'Заявки пользователя на вывод средств',

    )); ?>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

</form>
</div><!-- form -->