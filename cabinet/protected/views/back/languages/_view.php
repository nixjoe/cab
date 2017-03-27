<div class="view" style="cursor:pointer" onclick="$(this).next('div.dnone').toggle()">
<table>
	<thead>
		<td><?php echo CHtml::encode($data->getAttributeLabel('id')); ?></td>
		<td><?php echo CHtml::encode($data->getAttributeLabel('name')); ?></td>
		<td><?php echo CHtml::encode($data->getAttributeLabel('title')); ?></td>
		<td><?php echo CHtml::encode($data->getAttributeLabel('iso')); ?></td>
		<td><?php echo CHtml::encode($data->getAttributeLabel('sort')); ?></td>		
		<td><?php echo CHtml::encode($data->getAttributeLabel('active')); ?></td>				
	</thead>
	<tbody>
		<td><?php echo CHtml::encode($data->id); ?><input type='hidden' name='langs[<?= $data->id ?>][id]' value='<?php echo CHtml::encode($data->id); ?>'></td>
		<td><input type='text' name='langs[<?= $data->id ?>][name]' value='<?php echo CHtml::encode($data->name); ?>'></td>
		<td><input type='text' name='langs[<?= $data->id ?>][title]' value='<?php echo CHtml::encode($data->title); ?>'></td>
		<td><input type='text' name='langs[<?= $data->id ?>][iso]' style='width: 30px' value='<?php echo CHtml::encode($data->iso); ?>'></td>
		<td><input type='text' name='langs[<?= $data->id ?>][sort]' style='width: 30px' value='<?php echo CHtml::encode($data->sort); ?>'></td>		
		<td><input type='checkbox' name='langs[<?= $data->id ?>][active]' <?= $data->active?"checked='checked'":NULL ?>></td>	
	</tbody>
</table>
<a href="javascript:;" onclick="lDel(<?= $data->id ?>)">Удалить</a>
</div>

