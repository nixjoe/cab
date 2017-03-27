<?php
$this->breadcrumbs=array(
    'Настройки',
);
?>

<h1>Настройки</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'settings-form',
        'enableAjaxValidation'=>false,
    )); ?>

<ul class="nav nav-tabs">
    <li class="active"><a href="#birthday" data-toggle="tab">Письма с Днем рождения</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="birthday">
        <div class="row">
            <div class="span5">
                <?php echo $form->radioButtonList($model,'bd_sendType',
                    array(
                        '1'=>'Слать всем, за исключением',
                        '2'=>'Слать только',
                        '0'=>'Не слать'
                    ), array('labelOptions'=>array('class'=>'radio inline', 'style'=>'padding-left:5px'))); ?>
            </div>
            <div class="span6">
                <label>Страны</label>
                <?php
                $this->widget('ext.select2.ESelect2',array(
                    'model'=>$model,
                    'attribute'=>'bd_countries',
                    'data'=>CHtml::listData(Countries::model()->findAll(array('order' => 'rus ASC')), 'isoID', 'rus'),
                    'htmlOptions'=>array(
                        'multiple'=>'multiple',
                    ),
                    'options'=>array(
                        'width'=>'100%',
                    )
                ));
                ?>
            </div>
        </div>
        <div class="row form-actions">
            <?php echo CHtml::submitButton('Сохранить', array('class'=>'btn btn-primary btn-small')); ?>
            <?php echo CHtml::link('Отмена', array('settings'), array('class'=>'btn btn-link btn-small')); ?>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
