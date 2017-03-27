<script type="text/javascript">
    $('.firstload div:not(.active).foldable').each(function(){ $(this).hide()});
    $('.firstload').slideDown(300, function() { $(this).removeClass('firstload');}); 
</script>
<div class="firstload">
    <?php
    $i = 0;
    foreach ($messages as $kmessage => $message) {
        $i++;
        $inbox = ($message->sender == $this->user->ID);
        $unread = ($message->status == 1 && !$inbox);
        $active = ($unread || (count($messages) - $i < 2));
        ?>
        <div class="message <?= ($unread) ? 'unread' : '' ?>">
            <div class="tail <?= ($inbox) ? 'sent' : 'received' ?>"></div>
            <div class="text shadowed">
                <div class="msgheader unfolder <?= ($active) ? 'active' : '' ?>">        
                    <div class="sender inline <?= ($inbox) ? 'val' : 'curr' ?>"><?=
    ($inbox) ? Yii::t('messages', 'Вы') : ''
            // Нужно заменить на разовую загрузку. Ленивая генерирует лишние запросы. Проблема - вложенный запрос через Users без создания реляции.
            // Скорее всего нужно будет переписать на чистый SQL.
            /*(UsersManagers::model()->find(
                    array(
                        'condition' => "userID = :manager",
                        'params' => array(':manager' => $message->sender)
            ))->position_name)*/
    ?>
                    </div>
                    <div class="spoiler"><?= Yii::t('answers', $message->text) ?></div>
                    <div class="sentreceived"><?= $inbox ? '('.Yii::t('messages', 'Отправлено').')' : '('.Yii::t('messages', 'Получено').')' ?></div>
                    <div class="msgtime inline right curr">
            <?= Yii::app()->dateFormatter->formatDateTime(($message->datetime),'short',null) ?>
                    </div>
                </div>
                <div class="foldable msgbody <?= ($active) ? 'active' : '' ?>">
    <?= Yii::t('answers', nl2br($message->text)) ?>
                </div>
            </div>
        </div>
<?php $lastupdate = $message->ID; } ?>
 <?= ($this->action->id == 'reply') ? '' : '' // end of firstload div for replies?>     
</div>

<div id="async-<?=$thread_id?>">
<?php $this->widget('Flashes'); ?>
</div>
<?php if (isset($newcount)) {?>
<script type="text/javascript" >
    $('#t-<?=$thread_id?> .messagecount').html(<?=$newcount?>);
    <?php if (isset($lastupdate)) { ?>
        $('.last-<?=$thread_id?>').val(<?=$lastupdate?>);
    <?php }?>
</script>
<?php } ?>  