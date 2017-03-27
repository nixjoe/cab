<?php
$this->breadcrumbs=array(
	'Переводы'=>array('index'),
);

if(isset($_GET['cat'])) {
    $this->breadcrumbs[$_GET['cat']] = array('index', 'cat'=>$_GET['cat']);
}
$this->breadcrumbs[] = 'Coздать';

$this->menu=array(
	array('label'=>'Переводы','url'=>array('index')),
);
?>

<h1>Create MsgMessages</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>