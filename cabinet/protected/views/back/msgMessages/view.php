<?php
$this->breadcrumbs=array(
	'Msg Messages'=>array('index'),
	$model->ID,
);

$this->menu=array(
	array('label'=>'List MsgMessages','url'=>array('index')),
	array('label'=>'Create MsgMessages','url'=>array('create')),
	array('label'=>'Update MsgMessages','url'=>array('update','id'=>$model->ID)),
	/*array('label'=>'Delete MsgMessages','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->ID),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage MsgMessages','url'=>array('admin')),
	*/
);
?>

<h1>View MsgMessages #<?php echo $model->ID; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'ID',
		'datetime',
		'sender',
		'thread_id',
		'text',
		'status',
	),
)); ?>

<div class="row buttons">
		<table width="100%">
			<tbody><tr>
				
				<td style="text-align: right">
					<a id="a_user_msg" href="#user_msg">Отправить клиенту сообщение</a>
				</td>
			</tr>
		</tbody></table>

	
	</div>
<?php
	$cs=Yii::app()->getClientScript();
	$cs->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.fancybox.js', CClientScript::POS_END);
?>	

<script type="text/javascript">

jQuery(document).ready(function(){
			$('a#a_user_msg').fancybox({
				'hideOnContentClick': true,
'autoDimensions': false,
'width' : 600
			});
	});
</script>

<div style='display: none;' class='container'>
<form method='POST' id='user_msg'>
		<table>
			<tr>
				
				<td>
					<input name='send_msg[thread_id]' type='hidden' value="<?=$model->thread_id?>" />
					<input type="hidden" name="send_msg[lastupdate]"  value="<?=$model->ID?>">
				</td>
			</tr>
			<tr>
				<td>
					От кого:
				</td>
				<td>
					<select name='send_msg[sender]'>
					<?php
						foreach($managers as $man) :
					?>
						<option value='<?= $man['userID'] ?>'> <?= $man['position_name'] ?> </option>
					<?php
						endforeach;
					?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Сообщение:</td>
				<td><textarea name='send_msg[text]' style="width: 500px; height: 300px;"></textarea></td>
			</tr>
			<tr>
				<td><input type='hidden' name='send_msg[user]' value='<?=$model->sender?>'></td>
				<td><input type='submit' value='Отправить'></td>
			</tr>
		</table>
	</form>
</div>