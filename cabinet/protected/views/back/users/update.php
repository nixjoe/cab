<?php
$this->breadcrumbs=array(
	'Пользователи'=>array('index'),
	$model->givenName=>array('view','id'=>$model->ID),
	'Редактирование',
);

$this->menu=array(
	array('label'=>'Просмотреть записи', 'url'=>array('index')),
	array('label'=>'Просмотреть эту запись', 'url'=>array('view', 'id'=>$model->ID)),
	/*array('label'=>'Удалить запись', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Вы уверены?')),*/
);
?>
<?if (isset($messcount)){?>
<p>Новых сообшений <b>(<?=$messcount?>)</b></p>
<?}?>
<h2>Редактирование пользователя: <?php echo $model->givenName . " (#{$model->ID})"; ?></h2>

<?php echo $this->renderPartial('_form', array('model'=>$model,'mtdata'=>$mtdata,'fxtypes'=>$fxtypes, 'payouts'=>$payouts, 'params'=>$params,'transits'=>$transits,'tradeacc'=>$tradeacc,'transitid'=>$transitid,'payoutcredentials' => $payoutcredentials, 'userdoc' => $userdoc, 'managers' => $managers)); ?>
