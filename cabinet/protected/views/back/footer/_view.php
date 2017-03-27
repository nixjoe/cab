<div class="view" href="#">

    <div style="float:left">
        <b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
        <?php echo CHtml::encode($data->id); ?>
        <br />

        <b><?php echo CHtml::encode($data->getAttributeLabel('language')); ?>:</b>
        <?php echo CHtml::encode($this->getLangTitle($data->language)); ?>
        <br />

        <b><?php echo CHtml::encode($data->getAttributeLabel('publish')); ?>:</b>
        <?php echo CHtml::encode($data->publish? 'Да':'Нет'); ?>
        <br />
    </div>

    <div class="view-toolbar" style="float:right">
        <a href="<?php echo $this->createUrl('footer/update', array('id'=>$data->id)) ?>"><i class="icon icon-pencil"></i> Редактировать</a>
        &nbsp;
        <?php echo CHtml::ajaxLink('<i class="icon icon-remove"></i> Удалить',array('footer/delete','id'=>$data->id),array(
            'beforeSend' => 'js:function(){if(!confirm("Удалить?"))return false;}',
            'success'=>'js:function(data){$.fn.yiiListView.update("footers_list",{});}',
            'type'=>'post',
        ),array('class'=>'remove-link')); ?>
    </div>

    <div style="clear:both; font-size:0;height:0px;overflow:hidden">&nbsp;</div>

</div>