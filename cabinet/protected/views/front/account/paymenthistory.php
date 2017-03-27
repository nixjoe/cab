<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);

Yii::app()->clientScript->registerScript('end',"
    $('#filter-toggler').click(function(){
        $('#filter').slideToggle('fast','swing', function(){
            $('#filter-toggler').toggleClass('toggler-expanded');
            $('#filter-toggler .toggler-title').toggle();
        });
    });
    $('.filter-form form').submit(function(){
        $.fn.yiiGridView.update('payments-grid', {
            data: $(this).serialize()
        });
        return false;
    });

    function paymentsGridUpdateCallback(id) {
        $('#'+id+' .payment-cancel-link').off('click').on('click', function(e) {
            e.preventDefault();
            var pid = $(this).attr('data-id');
            if (pid && confirm('".Yii::t('payment', 'Вы уверены?')."')) {
                $.post('".Yii::app()->createUrl('/account/payoutcancel')."', {payment: pid}, function(){
                  $.fn.yiiGridView.update(id);
                });
            }
        });
    }

    paymentsGridUpdateCallback('payments-grid');
    ");
?>

<div class="cnt left">
    <h3><?= Yii::t('account', 'История платежей') ?></h3>
    <div class="separator"></div>

    <div id="filter-toggler" class="toggler toggler-right">
        <h3 class="toggler-title"><?= Yii::t('payment', 'Отобразить фильтр') ?></h3>
        <h3 class="toggler-title" style="display: none"><?= Yii::t('payment', 'Скрыть фильтр') ?></h3>
    </div>
    <div id="filter" class="content-box toggler-content" style="display: none">
        <div class="filter-form">
            <?php $this->renderPartial('_filter_payments',array(
                    'model'=>$model, 'types'=>$types, 'statuses'=>$statuses
                )); ?>
        </div>
    </div>

    <div class="clear"></div>
    <?php $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'payments-grid',
            'summaryText' => '',
            'itemsCssClass' => 'shadowed padtable content-box',
            'loadingCssClass' => 'grid-loading-fancy',
            'rowCssClass' => array('trow'),
            'dataProvider' => $model->filter(),
            'columns' => array(
                array(
                    'name'=>'date',
                    'value'=>'$data->formatDate()',
                ),
                array(
                    'name'=>'type',
                    'value'=>'$data->typeFormat()',
                ),
                array(
                    'name'=>'amount',
                    'value'=>'$data->formatAmount()',
                    'htmlOptions' => array('class'=>'curr'),
                ),
                array(
                    'name'=>'currency',
                    'value'=>'$data->formatCurrency()',
                ),
                array(
                    'name'=>'status',
                    'type'=>'raw',
                    'value'=>'$data->formatStatus().($data->status==1?\' <a href="#" class="payment-cancel-link" data-id="\'.$data->ID.\'">'.Yii::t('payment', 'Отменить').'</a>\':\'\')',
                ),
            ),
            'pagerCssClass' => 'pagination',
            'pager' => array(
                'header' => '',
                'htmlOptions' => array('class'=>'pages'),
                'cssFile' => CHtml::asset('css/pager.css'),
                'firstPageLabel' => '&lt;&lt;',
                'prevPageLabel' => '&lt;',
                'lastPageLabel' => '&gt;&gt;',
                'nextPageLabel' => '&gt;',
            ),
            'afterAjaxUpdate' => 'paymentsGridUpdateCallback'
        )); ?>
</div>