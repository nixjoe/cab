<div class="view" style="height: 50px; overflow: hidden;">
	<div style='width: 30px; float: left;'><?php echo CHtml::encode($data->language); ?> </div>
	<div style='width: 300px; float: left;'><?php echo CHtml::encode($data->slug); ?></div>
	<div style='clear: left; float: left; width: 100%;'><input type="text" style="width: 90%;" name="tr[<?php echo CHtml::encode($data->slug); ?>][<?php echo CHtml::encode($data->language); ?>]" value="<?php echo CHtml::encode($data->url); ?>"></div>
</div>