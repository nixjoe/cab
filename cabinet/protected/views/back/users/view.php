<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	$model->givenName,
);

$this->menu=array(
	array('label'=>'Просмотреть записи', 'url'=>array('index')),
	array('label'=>'Изменить', 'url'=>array('update', 'id'=>$model->ID)),
	/*array('label'=>'Удалить запись', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Вы уверены?')),*/

);
?>
<?if (isset($messcount)){?>
<p>Новых сообшений <b>(<?=$messcount?>)</b></p>
<?}?>
<h2>Просмотр пользователя: <?php echo $model->givenName . " (#{$model->ID})"; ?></h2>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'familyName',
		'givenName',
		'middleName',
		 array(
            'name'=>'regdate',
            'value'=>date("d/m/y", $model->regdate),
        ),
		'email',
		'phone',
	),
)); ?>

<h3>Кошелек FXPRIVATE: <span>FX<?php echo $transitid; ?></span></h3>
    <div  class="content-box">

     <?php foreach ($transits as $key=>$val) {
        echo "<div class='transits'>";
        echo "<div class='curr left'>{$val->currency_['alphaCode']}:</div>";
        echo "<div class='val right'>" . (floor($val['amount'] * 100)/100) . "</div>";
        echo "</div>";
    }    ?>
	</div>

<h3>Счета</h3>
	<table class="shadowed padtable content-box" rules="rows" bordercolor="blue" border="1" style="  border-radius: 5px 5px 5px 5px; background-color:#80CFFF;" >
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
            <td class=$leverageclass>
            1:$leverage
            </td>

        ";
    ?>
</tr>
    <?php
}
?>
</table>


<style>

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

.right {
    float: right !important;
}
</style>