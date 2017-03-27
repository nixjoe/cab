<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
        <div>
            <label><?php echo Yii::t('partner', 'Пожалуйста, выберите период:')?></label>
        </div>

        <?php
            for ($i = 0; $i <= 1; $i++) {
                echo ($i == 0 ? Yii::t('payment', 'с') : '&nbsp;&nbsp;' . Yii::t('payment', 'по')), '&nbsp;';
                $id = CHtml::activeId($model, 'date_'.$i);
                echo $form->hiddenField($model, 'date['.$i.']');
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'name'=>'date_'.$i,
                        'language'=>Yii::app()->language,
                        'options' => array(
                            'dateFormat'=>'d.m.yy',
                            'altField'=>'#'.$id,
                            'altFormat'=>'yy-mm-dd '.($i > 0 ? '23:59:59':'00:00:00')
                        ),
                        'htmlOptions' => array(
                            'class' => 'datepicker'
                        )
                    ));
            }
        ?>
	</div>

    <div class="row">
        <label style="line-height: 46px"><?php echo Yii::t('payment', 'Тип операции'); ?></label>
        <div class="select-container rel-container" style="width:170px">
            <div class="valuebox"><p></p>
            </div>
            <div class="radiobox autowidth">
                <div class="optcontainer">
                    <?=CHtml::activeRadioButtonList($model, 'type', $types, array(
                            'class'=>'overlayed',
                            'separator'=>''
                        ))?>
                </div>
            </div>
        </div>
        <?php //CHtml::activeDropDownList($model, 'type', $types)?>

        <label style="line-height: 46px;">&nbsp;&nbsp;<?php echo Yii::t('payment', 'Статус операции'); ?></label>
        <div class="select-container rel-container" style="width:170px">
            <div class="valuebox"><p></p>
            </div>
            <div class="radiobox">
                <div class="optcontainer">
                    <?=CHtml::activeRadioButtonList($model, 'status', $statuses, array(
                            'class'=>'overlayed',
                            'separator'=>''
                        ))?>
                </div>
            </div>
        </div>
        <?php //CHtml::activeDropDownList($model, 'status', $statuses)?>
        <div class="row buttons right" style="display: inline-block">
            <?php echo CHtml::submitButton(Yii::t('payment','Отфильтровать')); ?>
        </div>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->