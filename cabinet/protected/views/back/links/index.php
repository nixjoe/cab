<?php 
	$this->breadcrumbs=array(
		'links');
?>
В этом модуле редактируются ссылки для "Инструкции пользователя" и "Изменение личных данных в ЛК". Если использовать прямую ссылку на файл pdf, то нажав на нее пользователю браузер предложит скачать файл, поэтому для отображения инструкции используется google viewer. Для получение ссылки переходим на <a href='https://docs.google.com/viewer' target="_blank">https://docs.google.com/viewer</a> вводим ссылку на pdf файл(например, "https://my.fx-private.com/files/Инструкция пользователя ЛК.pdf"). После нажатия кнопки "создать ссылку" вам предложит 3 текстовых поля. Нужная ссылка находится в первом поле. 
Остальные ссылки можно редактировать в разделе "Переводы".  
<form method="POST">
<?php
 $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)) 		
?>
<input type="submit" name="save" value="Сохранить">
</form>