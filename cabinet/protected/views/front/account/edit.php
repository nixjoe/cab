<div class="cnt left">

<?php 

foreach ($fxtypes as $key=>$val) {
    if ($val['ID'] != $type['fxType']) {
        continue;
    }
    $leverage = explode(",",$val->leverage);
    $l = array();

    foreach ($leverage as $k=>$v) {$l[$v]="1:$v";};
?>

<div class="ui-accordion ui-widget ui-helper-reset ui-accordion-icons">
    <h3 class="ui-accordion-header ui-helper-reset ui-state-default ui-state-active ui-corner-top" ><span class="ui-icon ui-icon-carat-1-s"></span>
        <?=$val->name?>
	</h3>

    <div>
    <?php
        echo CHtml::beginForm(),
             CHtml::activeRadioButtonList($model,'leverage',$l,array('separator'=>' '));
             //CHtml::error($model,'leverage',array('class'=>'line-error '))';
    ?>
    <div class="form captcha">
        <br />
        <?php $this->widget('CCaptcha',array('id'=>'captcha-image','showRefreshButton'=>false,'clickableImage'=>true))?>
        <div><?=CHtml::activeLabel($model, 'verifyCode',array("class"=>"label-captcha label-left"))?></div>
        <?php
            echo CHtml::activeTextField($model, 'verifyCode', array("class"=>"captcha-input input-full","autocomplete"=>"off"));
            echo CHtml::error($model,'verifyCode',array('class'=>'line-error '));
        ?>
    </div>
    <?php
        echo
            CHtml::activeHiddenField($model, 'fxType', array('value'=>$val->ID)), '<br /><br />',
            CHtml::submitButton(Yii::t('account', 'Изменить плечо'),array('class'=>'greenbutton')),
        CHtml::endForm();
    ?>
    </div>
</div>
<?php }?>