<?php
$this->breadcrumbs=array(
	'MultiLanguages'=>array('index'),
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
