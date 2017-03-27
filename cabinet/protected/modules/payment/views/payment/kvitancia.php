<?
global $_1_2;
global $_1_19;
global $des;
global $hang;
global $namerub;
global $nametho;
global $namemil;
global $namemrd;
global $kopeek;

$_1_2[1]="одна ";
$_1_2[2]="две ";

$_1_19[1]="один ";
$_1_19[2]="два ";
$_1_19[3]="три ";
$_1_19[4]="четыре ";
$_1_19[5]="пять ";
$_1_19[6]="шесть ";
$_1_19[7]="семь ";
$_1_19[8]="восемь ";
$_1_19[9]="девять ";
$_1_19[10]="десять ";

$_1_19[11]="одиннацать ";
$_1_19[12]="двенадцать ";
$_1_19[13]="тринадцать ";
$_1_19[14]="четырнадцать ";
$_1_19[15]="пятнадцать ";
$_1_19[16]="шестнадцать ";
$_1_19[17]="семнадцать ";
$_1_19[18]="восемнадцать ";
$_1_19[19]="девятнадцать ";

$des[2]="двадцать ";
$des[3]="тридцать ";
$des[4]="сорок ";
$des[5]="пятьдесят ";
$des[6]="шестьдесят ";
$des[7]="семьдесят ";
$des[8]="восемдесят ";
$des[9]="девяносто ";

$hang[1]="сто ";
$hang[2]="двести ";
$hang[3]="триста ";
$hang[4]="четыреста ";
$hang[5]="пятьсот ";
$hang[6]="шестьсот ";
$hang[7]="семьсот ";
$hang[8]="восемьсот ";
$hang[9]="девятьсот ";

/*switch($currency) {
	case 'EUR':
		$namerub[1]="евро ";
		$namerub[2]="евро ";
		$namerub[3]="евро ";
		break;
	case 'UAН':
		$namerub[1]="гривня ";
		$namerub[2]="гривни ";
		$namerub[3]="гривен ";
		break;
	case 'USD':
		$namerub[1]="гривня ";
		$namerub[2]="гривни ";
		$namerub[3]="гривен ";
		break;
}*/

$namerub[1]="гривня ";
$namerub[2]="гривни ";
$namerub[3]="гривен ";

$nametho[1]="тысяча ";
$nametho[2]="тысячи ";
$nametho[3]="тысяч ";

$namemil[1]="миллион ";
$namemil[2]="миллиона ";
$namemil[3]="миллионов ";

$namemrd[1]="миллиард ";
$namemrd[2]="миллиарда ";
$namemrd[3]="миллиардов ";

$kopeek[1]="копейка ";
$kopeek[2]="копейки ";
$kopeek[3]="копеек ";


function semantic($i,&$words,&$fem,$f){
global $_1_2, $_1_19, $des, $hang, $namerub, $nametho, $namemil, $namemrd;
$words="";
$fl=0;
if($i >= 100){
$jkl = intval($i / 100);
$words.=$hang[$jkl];
$i%=100;
}
if($i >= 20){
$jkl = intval($i / 10);
$words.=$des[$jkl];
$i%=10;
$fl=1;
}

switch($i){
case 1: $fem=1; break;
case 2:
case 3:
case 4: $fem=2; break;
default: $fem=3; break;
}
if( $i ){
if( $i < 3 && $f > 0 ){
if ( $f >= 2 ) {
$words.=$_1_19[$i];
}
else {
$words.=$_1_2[$i];
}
}
else {
$words.=$_1_19[$i];
}
}
}


function num2str($L){
global $_1_2, $_1_19, $des, $hang, $namerub, $nametho, $namemil, $namemrd, $kopeek;

$s=" ";
$s1=" ";
$s2=" ";
$kop=intval( ( $L*100 - intval( $L )*100 ));
$L=intval($L);

if($L>=1000000000){
$many=0;
semantic(intval($L / 1000000000),$s1,$many,3);
$s.=$s1.$namemrd[$many];
$L%=1000000000;
}

if($L >= 1000000){
$many=0;
semantic(intval($L / 1000000),$s1,$many,2);
$s.=$s1.$namemil[$many];
$L%=1000000;
if($L==0){
$s.="гривен ";
}
}

if($L >= 1000){
$many=0;
semantic(intval($L / 1000),$s1,$many,1);
$s.=$s1.$nametho[$many];
$L%=1000;
if($L==0){
$s.="гривен ";
}
}

if($L != 0){

$many=0;
semantic($L,$s1,$many,0);	
$s.=$s1.$namerub[$many];
}

if($kop > 0){
$many=0;
semantic($kop,$s1,$many,1);
$s.=$s1.$kopeek[$many];
}
else {
$s.=" 00 копеек";
}

return $s;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=UTF-8" http-equiv="content-type">
<meta content="ru" name="language">

<style type="text/css">
	body {
		font-size: 12px;	
	}
	
	.box {
		border: 1px solid black;
	}
	
	.top {
		border-top: 1px solid black;
	}
	
	.bottom {
		border-bottom: 1px solid black;	
	}
	
	.left {
		border-left: 1px solid black;				
	}
	
	.right {
		border-right: 1px solid black;				
	}
	
	.box2 {
		border: 2px solid black;
	}
	
	.top2 {
		border-top: 2px solid black;
	}
	
	.bottom2 {
		border-bottom: 2px solid black;	
	}
	
	.left2 {
		border-left: 2px solid black;				
	}
	
	.right2 {
		border-right: 2px solid black;				
	}
	
	td {
		padding: 5px;	
		font-size: 12px;
	}
</style>
</head>
<body>
<div style="padding-left: 200px">
	<b>Счёт №<?= $payment_id ?><br> от <?= date('m.d.Y') ?></b>
</div>
<table cellpadding="0" cellspacing="0">
	<tr>
		<td class="box2" width="160">Плательщик:</td>	
		<td class="bottom" width="300"><?= $username ?></td>	
	</tr>
	<tr>
		<td colspan="2" height="20"></td>	
	</tr>
	<tr>
		<td class="box2">Получатель:</td>	
		<td class="bottom">«ЕФ ІКС ПРИВАТ КАМПАНІ», ТОВ</td>	
	</tr>
	<tr>
		<td colspan="2" height="20"></td>	
	</tr>
	<tr>
		<td class="box2">Реквизиты получателя:</td>	
		<td class="top2 right2">&nbsp;</td>		
	</tr>
	<tr>
		<td class="left2 bottom right" style="text-align: right">Р/Р:</td>	
		<td class="bottom right2">26008500049196</td>		
	</tr>
	<tr>
		<td class="left2 bottom right" style="text-align: right">в:</td>	
		<td class="bottom right2">ПАТ «Креді Агріколь Банк»</td>		
	</tr>
	<tr>
		<td class="left2 bottom right" style="text-align: right">МФО:</td>	
		<td class="bottom right2">300614</td>		
	</tr>
	<tr>
		<td class="left2 bottom right" style="text-align: right">ЭДРПОУ:</td>	
		<td class="bottom right2">38138561</td>		
	</tr>
	<tr>
		<td class="left2 bottom2 right" style="text-align: right">Телефон:</td>	
		<td class="bottom2 right2">(044) 2514751</td>		
	</tr>
</table>
<br><br>
<table cellpadding="0" cellspacing="0">
	<tr>
		<td width="20" class="bottom right left2 top2">№<br>п/п</td>	
		<td width="440" class="bottom right top2"> Наименование </td>	
		<td width="160" class="bottom top2 right2"> Сумма </td>	
	</tr>
	<tr>
		<td class="bottom2 right left2">1</td>	
		<td class="bottom2 right"> Оплата рахунку за послуги №<?= $payment_id ?> от <?= date('m.d.Y') ?> </td>	
		<td class="bottom2 right2" align="right"> <?= number_format($amount, 2) ?> </td>	
	</tr>
	<tr>
		<td colspan="2" align="right"> НДС </td>	
		<td class="bottom left right2"> Без НДС </td>	
	</tr>
	<tr>
		<td colspan="2" align="right"> Всего </td>	
		<td class="bottom left right2" align="right"> <?= number_format($amount, 2) ?> </td>	
	</tr>
</table><br><br>

<b>Назначение платежа:<br></b>
<table>
<tr>
<td width='640' class="bottom" colspan="2">
<b> Оплата рахунку за послуги №<?= $payment_id ?> от <?= date('m.d.Y') ?></b>
</td>
</tr>
<tr>
	<td colspan="2">
		&nbsp;&nbsp;&nbsp;&nbsp;Всего (прописью):	
	</td>
</tr>
<tr>
	<td class="bottom" colspan="2">

		<?= num2str(number_format($amount, 2, '.', '')) ?>. Без НДС
	</td>
</tr>
<tr>
	<td valign="middle" align="right">
		М.П. 	
	</td>
	<td>
	<img src="images/stump.jpg" width="180" alt="" >
	</td>
</tr>
</table>
</body>
</html>