<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<?

function show_status($status){
    switch ($status){
        case 0:
            return 'Исполнена';
        case 5:
            return 'Отменена клиентом';
        case 2:
            return 'Отклонена';
        default:
            return 'Принята';
    }
}

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'payouts-grid',
    'dataProvider'=>$transfers,
    'columns'=>array(
        array(
            'name' => 'date',
            'header' => 'Дата'
        ),
        array(
            'name' => 'amount',
            'header' => 'Сумма',
            'value'=>'round($data["amount"], 2)'
        ),
        array(
            'name' => 'title',
            'header' => 'ФИО',
            'value' => '$data["issuer_"]["familyName"]." ".$data["issuer_"]["givenName"]." ".$data["issuer_"]["middleName"] '
        ),
        array(
            'name' => 'add_info',
            'header' => 'Содержание заявки',
            'type'=>'raw'
        ),
        array(
            'name' => 'status',
            'header' => 'Статус',
            'value' => 'show_status($data["status"])'
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}',
            'buttons'=>array
            (
                'update' => array
                (
                    'url'=>'Yii::app()->createUrl("users/update", array("id"=>$data->issuer))',
                ),
            )
        )
    ),
    'enableSorting' => false,
    'enablePagination' => true,
    'summaryText' => '<h3>Заявки пользователя на вывод средств</h3>',

));

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'messages-grid',
    'dataProvider'=>$messages,
    'columns'=>array(
        array(
            'name' => 'datetime',
            'header' => 'Дата'
        ),
        array(
            'name' => 'ID',
            'header' => 'ID'
        ),
        array(
            'name' => 'title',
            'header' => 'ФИО',
            'value' => '$data["thread_"]["client_"]["familyName"]." ".$data["thread_"]["client_"]["givenName"]." ".$data["thread_"]["client_"]["middleName"] '
        ),
        array(
            'name' => 'email',
            'header' => 'Email',
            'value' => '$data["thread_"]["client_"]["email"]'
        ),
        array(
            'name' => 'phone',
            'header' => 'Телефон',
            'value' => '$data["thread_"]["client_"]["phone"]'
        ),
        array(
            'name' => 'text',
            'header' => 'Текст сообщения',
            'value' => 'substr($data["text"], 0, 100)'
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{view}',
            'buttons'=>array
            (
                'view' => array
                (
                    'url'=>'Yii::app()->createUrl("msgMessages/view", array("id"=>$data->ID))',
                ),
            )
        )
    ),
    'enableSorting' => false,
    'enablePagination' => true,
    'summaryText' => '<h3>Новые сообщения</h3>',

));

?>




<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>