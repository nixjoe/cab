<?php 
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/messages.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.mousewheel.js", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/relcontainer.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);

?>

<?php $this->beginContent('//layouts/cabinet'); ?>
<div class="scrollup-relative">
    <div class="scrollup-fixed">
        <a class="scrollup" href="javascript:">
            <?= Yii::t('messages', 'На верх') ?>
        </a>        
    </div>
</div>    
<div class="left">
<div class="faqmenu shadowed left">
<?=
CHtml::link("
    <div class='menuitem " . (empty($this->filter) ? 'active' : '') . "'>
        <div class='text' id='switch'>". Yii::t('messages', 'Все сообщения') ."<div class='right threadcount'>" . $this->thread_count .  "</div></div>
    </div>",
        array('/messages/index/')
        )?>
<?php 
$i=0;
foreach ($this->types as $k=>$v) { $i++;
    echo CHtml::link("
    <div class='menuitem " . (!empty($this->filter) && ($this->filter->slug == $v->slug) ? 'active' : '') . "'>
        <div class='text'>".Yii::t('messages', $v->title)."<div class='right threadcount'>".($i == 1 ? $this->thread_count : $v->thread_count)."</div></div>
        <div class=''></div>
    </div>", Yii::app()->createUrl('/messages/index/', array('filter'=>$v->slug)),array('class'=>''));
}
?>
<?/* <div class='text'>{$v->title}<div class='right threadcount'>{$v->thread_count}</div></div>*/?>
</div>
</div>
<?php echo $content; ?>


<?php $this->endContent(); ?>