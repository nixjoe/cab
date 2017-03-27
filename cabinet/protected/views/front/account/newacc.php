<div class="cnt left">
    <h3><?= Yii::t('account', 'Открытие нового <span>Торгового</span> счета') ?></h3>
    <div class="separator full-center"></div>
    <div>
        <?php echo Yii::t('account', 'Выберите тип торгового счета') ?>:
    </div>
<?php 
foreach ($fxtypes as $key=>$val) {
    $leverage = explode(",",$val->leverage);
    $l = array();
    foreach ($leverage as $k=>$v) {$l[$v]="1:$v";};
    $f[$val->name] = CHtml::beginForm() .
            '<div>'.Yii::t('account', 'Выберите желаемое кредитное плечо').':</div>' .
            CHtml::activeRadioButtonList($model,'leverage',$l,array('separator'=>' ')) . "<br/><br/>" . 
            CHtml::activeHiddenField($model, 'fxType', array('value'=>$val->ID)) .
            CHtml::submitButton(Yii::t('main', 'Открыть счет'),array('class'=>'greenbutton')) .
            CHtml::endForm();
}
?>

    <?php 

    $this->widget('zii.widgets.jui.CJuiAccordion', array(
    'panels'=>$f,
    // additional javascript options for the accordion plugin
    'options'=>array(
        'animated'=>'slide',
        'autoHeight'=>false,
        'navigation'=>true,
        'icons'=> array(
            'header'=>'ui-icon-carat-1-n',
            'headerSelected'=>'ui-icon-carat-1-s',
        )
        
    ),
        'themeUrl'=>Yii::app()->request->baseUrl . "/css",
        'theme'=>'transparent'
));  
?>
</div>