<?php
    function kcfinder_select($c, $model, $attribute, array $options=array()) {
        return $c->widget('application.extensions.wysiwyg.KCFinder', array_merge(
            array(
                'type' => 'files',
                'model' => $model,
                'attribute' => $attribute,
                'filespath'=>dirname(Yii::app()->basePath) . DIRECTORY_SEPARATOR . 'media',
                "filesurl"=>Yii::app()->baseUrl . '/media',
            ), $options
        ));
    }
?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'banners-form',
    'enableAjaxValidation'=>false,
    'action' => Yii::app()->createUrl('partner/saveAgreement')
));
?>
<p><b>Партнерское соглашение</b></p>

<ul class="nav nav-tabs">
    <?php foreach($languages as $i=>$lang): ?>
        <li<?php if($i==0) echo ' class="active"'; ?>>
            <a href="#partner-agree-<?=$lang->iso?>" data-toggle="tab"><?= $lang->name ?></a>
        </li>
    <?php endforeach; ?>
</ul>
<div class="tab-content">
    <?php foreach($languages as $i=>$lang): ?>
        <div class="tab-pane<?php if($i==0) echo ' active'; ?>" id="partner-agree-<?=$lang->iso?>">
            <?= $lang->title ?>
            <?php
                kcfinder_select($this, null, null, array('name'=>'agreement['.$lang->iso.']','value'=>$model->agreement[$lang->iso]));
            ?>
        </div>
    <?php endforeach; ?>
</div>
<hr />
<?php echo CHtml::submitButton('Сохранить', array('class'=>'btn btn-primary')); ?>
<?php $this->endWidget(); ?>