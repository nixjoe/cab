<script type='text/javascript'>
	function printRetum() {
		var pw = window.open("about:blank","_new");
		pw.document.open();
		pw.document.write("<html>\n" +
			"<head>\n" +
			"</head>\n" +
			"<body onLoad='window.print();'>\n" +
				"<table>\n" +
					"<tr>\n" +
						"<td>Beneficiary's Name</td>\n" +
						"<td>FXPRIVATE COMPANY LTD</td>\n" +
					"</tr>\n" +
					"<tr>\n" +
						"<td>Beneficiary's Account Number (IBAN):</td>\n" +
						"<td>LV 50RTMB 0000621806043</td>\n" +
					"</tr>\n" +
					"<tr>\n" +
						"<td>Beneficiary's Address</td>\n" +
						"<td>#1 Mapp Street, Belize City,  Belize.</td>\n" +
					"</tr>\n" +
					"<tr>\n" +
						"<td>Bank Name</td>	\n" +
						"<td>Rietumu Bank</td>	\n" +
					"</tr>\n" +
					"<tr>  \n" +
						"<td>Bank SWIFT Code</td>	\n" +
						"<td>RTMBLV2X</td>\n" +	
					"</tr>\n" +
					"<tr>\n" +
						"<td>Bank Address</td>	\n" +
						"<td>7 Vesetas str. Riga, LV-1011, Latvia.</td>	\n" +
					"</tr>\n" +
					"<tr>\n" +
						"<td>Details of Payment (Comment)</td>	\n" +
						"<td>Payment for the Agreement – <?php echo $payment_id ?>*</td>	\n" +
					"</tr>\n" +
				"</table>\n</html>\n");
		pw.document.close();
	}
</script>
<div class="cnt left">
<table>
	<tr>
		<td>Beneficiary's Name</td>	
		<td>FXPRIVATE COMPANY LTD</td>	
	</tr>
	<tr>
		<td>Beneficiary's Account Number (IBAN):</td>	
		<td>LV 50RTMB 0000621806043</td>	
	</tr>
	<tr>
		<td>Beneficiary's Address</td>	
		<td>#1 Mapp Street, Belize City,  Belize.</td>	
	</tr>
	<tr>
		<td>Bank Name</td>	
		<td>Rietumu Bank</td>	
	</tr>
	<tr>
		<td>Bank SWIFT Code</td>	
		<td>RTMBLV2X</td>	
	</tr>
	<tr>
		<td>Bank Address</td>	
		<td>7 Vesetas str. Riga, LV-1011, Latvia.</td>	
	</tr>
	<tr>
		<td>Details of Payment (Comment)</td>	
		<td>Payment for the Agreement – <?php echo $payment_id ?>*</td>	
	</tr>

</table>
<b>* <?= Yii::t('payment', 'ПЛАТЕЖИ С КОММЕНТАРИЕМ, ОТЛИЧНЫМ ОТ УКАЗАННОГО В РЕКВИЗИТАХ ДЛЯ ПОПОЛНЕНИЯ, ЗАЧИСЛЯТЬСЯ НЕ БУДУТ. В СЛУЧАЕ ПОЛУЧЕНИЯ ТАКОГО ПЛАТЕЖА СРЕДСТВА БУДУТ ОТПРАВЛЕНЫ ОБРАТНО ЗА ВЫЧЕТОМ БАНКОВСКИХ КОМИССИЙ.</b><br>
Пожалуйста,  для ускорения зачисления Ваших средств на счет пройдите процедуру') ?>: <br><br>
<? echo CHtml::submitButton(Yii::t('payment', 'Уведомить о платеже'), array('onclick' => ' window.location.href = \''.Yii::app()->createUrl("account/notify").'\'; return false;', 'class' => 'greybutton', 'style' => 'margin-left: 10px;')); ?>
<a onClick="printRetum();" href="#"><?= Yii::t('payment', 'Распечатать реквизиты') ?></a>
</div>																															