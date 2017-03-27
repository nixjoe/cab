<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) { ?>
<div class="flash <?=$key?>">
    <p class="close"/>
    <div class="header"><?=$message['header']?></div>
    <?=$message['text']?>
</div>
<?php    }?>