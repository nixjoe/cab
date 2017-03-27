<?
$connection=Yii::app()->db;
		
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '433' ";				
		$tr1=$connection->createCommand($sql)->queryRow();
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '430' ";				
		$tr2=$connection->createCommand($sql)->queryRow();
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '431' ";				
		$tr3=$connection->createCommand($sql)->queryRow();
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '432' ";				
		$tr4=$connection->createCommand($sql)->queryRow();
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '442' ";				
		$tr5=$connection->createCommand($sql)->queryRow();
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '437' ";				
		$tr6=$connection->createCommand($sql)->queryRow();
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '468' ";				
		$tr7=$connection->createCommand($sql)->queryRow();
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '469' ";				
		$tr8=$connection->createCommand($sql)->queryRow();
		$sql= "SELECT `message` FROM `sourcemessage` WHERE `id` = '470' ";				
		$tr9=$connection->createCommand($sql)->queryRow();
		
?>
<div class="cnt left">
    <h3><?= Yii::t('partner', 'Партнёрская ссылка') ?></h3>
   <div class="separator"></div>
   <?=CHtml::link(Yii::t('partner', $tr3['message']), "#", array('id'=>'genlink1', 'class'=>'greenbutton right-marged'))?>
    <br /> <br />
   <?=CHtml::link(Yii::t('partner', $tr2['message']), "#", array('id'=>'genlink2', 'class'=>'greenbutton right-marged'))?>
   <br />
   <div class="form" id="mylink1" style="display:none; padding-top:40px;">
 
	<div class="row" >
		<?
		
		
		$sql= "SELECT * FROM `partnerinfo` WHERE `key` = 'url' ORDER BY `order`";				
		$data=$connection->createCommand($sql)->queryAll();
		if (isset($data) && !empty($data)){
			echo '<label>'.Yii::t('partner', $tr1['message']).'</label>';
			echo '<br />';
			foreach ($data as $items){
				echo '<input class="radio1" type="radio" name="radio1" value="'.$items['value'].'"  /><label>'.Yii::t('partner',$items['lnurl']).'</label><br />';
			}
		}
		echo '<br />';
		$sql= "SELECT * FROM `partnerinfo` WHERE `key` = 'lang' ORDER BY `order`";				
		$data=$connection->createCommand($sql)->queryAll();
		if (isset($data) && !empty($data)){
			echo '<label>'.Yii::t('partner', $tr5['message']).'</label>';
			echo '<br />';
			foreach ($data as $items){
				echo '<input class="radio2" type="radio" name="radio2" value="'.$items['value'].'"  /><label>'.$items['lnurl'].'</label><br />';
			}
		}
		?>
		<br />
		<label for="LoginForm_username" class="label-left"><?= Yii::t('partner', $tr6['message']) ?></label>		
		<br />
		<input style="width:500px;" value="" type="text" class="input-full" />		
		</div>
	</div>
	<div class="form" id="mylink2" style="display:none; padding-top:40px;">
   <div class="row" >
		
		<?
		$connection=Yii::app()->db;
		$sql= "SELECT * FROM `languages` WHERE `active` = '1' ORDER BY `sort`";				
		$data=$connection->createCommand($sql)->queryAll();
		if (isset($data) && !empty($data)){
			echo '<label>'.Yii::t('partner', $tr4['message']).'</label>';
			echo '<br />';
			foreach ($data as $items){
				echo '<input type="radio" name="radio3" value="'.$items['iso'].'"  /><label>'.$items['title'].'</label><br />';
			}
		}
		?>
		<br />
		<label for="LoginForm_username" class="label-left"><?= Yii::t('partner', $tr6['message']) ?></label>		
		<br />
		<input style="width:500px;" value="" type="text" class="input-full" />	
	</div>
	</div>
	<br />
	<p><?echo Yii::t('partner', $tr7['message']);?></p>
	<p><?echo Yii::t('partner', $tr8['message']);?></p>
	<?$url = explode('/', $_SERVER['REDIRECT_URL']);?>
	<p style="color:#61B3E2;"><a target="_blank" href="http://www.fx-private.com/<?=$url[1] ? $url[1].'/': ''?>partners.html"><?echo Yii::t('partner', $tr9['message']);?></a></p> 
   <script type="text/javascript">
			var href = "https://my.fx-private.com/";
			var url = "/auth/register/<?=$refID?>";
			var id = "?rid=<?=$refID?>";
			$(document).ready(function(){
				$('#mylink2 input[type="radio"]').click(function(){
					$('#mylink2 .input-full').val(href+$(this).val()+url);
				});
				$('#mylink1 input[type="radio"]').click(function(){
					if ($('.radio1:checked').size() > 0 && $('.radio2:checked').size() > 0){
						var siteurl = $('.radio1:checked').val();
						var lang = $('.radio2:checked').val();
						var mass = siteurl.split('fx-private.com');
						var first = mass[0];
						$('#mylink1 .input-full').val(first + 'fx-private.com/' + lang + mass[1] + id);
					}
				});
				$('#genlink1').click(function(e){
					e.preventDefault();
					$('#mylink2').hide();
					$('#mylink1').fadeIn(300);
				});
				$('#genlink2').click(function(e){
					e.preventDefault();
					$('#mylink1').hide();
					$('#mylink2').fadeIn(300);
				});
			});
   </script>
   
</div>
  