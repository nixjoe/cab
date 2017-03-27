<div class="view" style="">
	<input type="text" style="width: 90%;" name="partner[<?php echo CHtml::encode($data->id); ?>]" value="<?php echo CHtml::encode($data->value); ?>">
	<input type="text" style="width: 90%;" name="lnuri[<?php echo CHtml::encode($data->id); ?>]" value="<?php echo CHtml::encode($data->lnurl); ?>">
	<input type="text" style="width: 90%;" name="order[<?php echo CHtml::encode($data->id); ?>]" value="<?php echo CHtml::encode($data->order); ?>">
	<input type="submit" name="remove[<?php echo CHtml::encode($data->id); ?>]" value="Удалить" />
</div>