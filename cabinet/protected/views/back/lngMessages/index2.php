<?php
$this->breadcrumbs=array(
	'Переводы'=>array('index'),
);

$this->menu=array(
    array('label'=>'Создать','url'=>array('create')),
);

?>

<form method="GET">
	<input type="text" name='search_text' value="<?= isset($_GET['search_text']) ? $_GET['search_text'] : NULL ?>"/>
	<input type="hidden" name="r" value="lngMessages">
	<input type="submit"/>
</form>
<?php

 $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view2',
)) 
?>
