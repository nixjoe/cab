<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);

Yii::app()->clientScript->registerScript('end',"
    var _grid_width = $('#trade-history-grid table:first').outerWidth();
    if (_grid_width > 0) {
        $('#trade-table-info, #trade-info-separator').width(_grid_width);
    }
");
?>

<div class="cnt left" style="min-height: 100px">
    <h3><?php echo Yii::t('partner', 'STATISTICS_TITLE') ?></h3>
    <div class="separator"></div>

    <div>
        <div class="filter-form">
            <div class="form">
                <div class="row">
                    <p class="label-left"/>
                    <div class="full-center"><?$this->widget('Flashes'); ?></div>
                </div>

                <?php $form=$this->beginWidget('CActiveForm'); ?>

                <div class="rowleft">
                    <?php echo $form->label($model,'account'); ?><br />
                    <div class="select-container rel-container" style="width:190px">
                        <div class="valuebox"><p></p>
                        </div>
                        <div class="radiobox radiolist-full">
                            <div class="optcontainer">
                                <?=CHtml::activeRadioButtonList($model, 'account', $accounts, array(
                                    'class'=>'overlayed',
                                    'separator'=>''
                                ))?>
                            </div>
                        </div>
                    </div>
                    <?php //CHtml::activeDropDownList($model, 'account', $accounts)?>
                </div>

                <div class="rowleft">
                    <label><?php echo Yii::t('partner', 'Пожалуйста, выберите период:')?></label><br />
                    <?php
                    for ($i = 0; $i <= 1; $i++) {
                        echo ($i == 0 ? Yii::t('payment', 'с') : '&nbsp;&nbsp;' . Yii::t('payment', 'по')), '&nbsp;';
                        $attr = $i == 0 ? 'fromDate' : 'toDate';
                        $id = CHtml::activeId($model, $attr);
                        echo $form->hiddenField($model, $attr);
                        $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                            'model'=>$model,
                            'id'=>$id.'Txt',
                            'attribute'=>$attr.'Txt',
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
                    <?php echo $form->label($model,'password', array('label'=>Yii::t('trade','Введите пароль инвестора от выбраного счета'))); ?><br />
                    <?php echo $form->passwordField($model,'password') ?>
                </div>

                <div class="row buttons">
                    <?php echo CHtml::submitButton(Yii::t('account','Показать')); ?>
                </div>

                <?php $this->endWidget(); ?>
            </div><!-- form -->
        </div>
    </div>

</div>

<div class="clear"></div>

<div class="full-center-cnt">
    <div class="clear"></div>
    <?php $this->widget('application.components.widgets.GridViewExt', array(
        'id'=>'trade-history-grid',
        'summaryText' => '',
        'itemsCssClass' => 'shadowed padtable content-box widetable',
        'loadingCssClass' => 'grid-loading-fancy',
        'rowCssClass' => array('trow'),
        'dataProvider' => $data,
        'columns' => array(
            array(
                'header'=>Yii::t('account','Order'),
                'name'=>'order',
                'value'=>'$data["order"]',
            ),
            array(
                'header'=>Yii::t('account','Open Time'),
                'name'=>'time',
                'value'=>'$data["time"]',
            ),
            array(
                'header'=>Yii::t('account','Type'),
                'name'=>'type',
                'value'=>'$data["type"]',
            ),
            array(
                'header'=>Yii::t('account','Volume'),
                'name'=>'lots',
                'value'=>'$data["lots"]',
            ),
            array(
                'header'=>Yii::t('account','Symbol'),
                'name'=>'symbol',
                'value'=>'strtolower($data["symbol"])',
            ),
            array(
                'header'=>Yii::t('account','Price'),
                'name'=>'price',
                'value'=>'$data["price"]',
            ),
            array(
                'header'=>Yii::t('account','S/L'),
                'name'=>'sl',
                'value'=>'$data["sl"]',
            ),
            array(
                'header'=>Yii::t('account','T/P'),
                'name'=>'tp',
                'value'=>'$data["tp"]',
            ),
            array(
                'header'=>Yii::t('account','Close Time'),
                'name'=>'close_time',
                'value'=>'$data["close_time"]',
            ),
            array(
                'header'=>Yii::t('account','Price'),
                'name'=>'price2',
                'value'=>'$data["price2"]',
            ),
            array(
                'header'=>Yii::t('account','Commission'),
                'name'=>'commission',
                'value'=>'$data["commission"]',
            ),
            array(
                'header'=>Yii::t('account','Swap'),
                'name'=>'swap',
                'value'=>'$data["swap"]',
            ),
            array(
                'header'=>Yii::t('account','Profit'),
                'name'=>'profit',
                'value'=>'$data["profit"]',
            ),
        ),
        'footerData' => $footerData,
    )); ?>

    <div class="separator" id="trade-info-separator"></div>

    <?php $this->renderPartial('_info',array(
        'info'=>$info
    )); ?>
</div>