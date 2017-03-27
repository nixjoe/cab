<div class="view">
	<b><?php echo CHtml::encode($data->getAttributeLabel('category')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->category),array('viewCategory','cat'=>$data->category)); ?>
</div>