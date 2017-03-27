
<div class="cnt left">
	<h3><?= Yii::t('partner', 'Мои привлеченные клиенты') ?></h3>
	<div class="separator"></div>
	
	<?if (isset($clients)  && count($clients) > 0){?>
     
		<table bordercolor="blue" border="1" frame="void" rules="rows" class="shadowed padtable content-box">
			 <tr class="headrow">
					<th><?= Yii::t('partner', 'Номер торгового счета') ?></th>
					<th><?= Yii::t('partner', 'Дата привлечения') ?></th>
					<th><?= Yii::t('partner', 'Тип счёта') ?></th>
					<th><?= Yii::t('partner', 'ФИО реферала') ?></th>
			</tr>
			<?foreach($clients as $key => $items){?>
				 <tr class="trow">
					<td class="curr"><?=$items['item']['login']?></td>
					<td><?=date('d.m.Y', $items['item']['regdate'])?></td>
					<td><?=$items['item']['group']?></td>
					<td><?=$items['user']['familyName']?> <?=$items['user']['givenName']?> <?=$items['user']['middleName']?></td>
				</tr>
			<?}?>
		</table>
	<?}else{?>
		<p><?= Yii::t('partner', 'Нет привлечённых клиентов!') ?></p>
	<?}?>

    <br />
    <p><?= Yii::t('partner', 'ABOUT_CLIENTS') ?></p>

</div>