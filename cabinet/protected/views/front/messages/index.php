<?php
// Эта часть страницы золжна загружаться только один раз - при первичном запросе.
// При асинхронных запросах выполнение этого кода приведет к некорректной работе системы.
if (!Yii::app()->request->isAjaxRequest) {
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/unfolder.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.autosize-min.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScript('msghead1',
        // Отображение ссылки наверх:
        "$(window).scroll(function(){
            if (isScrolledIntoView('#header-bottom')) {
                $('.scrollup:visible').fadeOut(100)
            } else {
                $('.scrollup:hidden').fadeIn(100)
            }
        });
        $('.scrollup').click(function() {
            $('html, body').animate({scrollTop:0}, 'medium');
        });",
        CClientScript::POS_READY);
    Yii::app()->clientScript->registerScript('msg2',
            // Когда человек кликает на заголовок непрочитанного письма - отметим его, как прочитанное
            "$('.message.unread .unfolder').live('click', function() { $(this).parent().parent().switchClass('unread', '')}); " .
            // Закрытие ветки сообщений
            "$('a.close-text').live('click', function() { $(this).parents('.foldable').siblings('.unfolder').click(); } );" .
            // Красивое увеличение инпутов под текст: 
            "$('textarea').autosize();" .
            // Загружаем сообщения аяксом:
            "
        $('.loader').live('click', function(){
            element = $(this);
            id = $(this).attr('id');
            $.ajax({
                    type: 'POST',
                    url: '" . Yii::app()->createUrl('/messages/loadthread', array('thread_id' => '')) . "' + id,
                    success: function(data) {
                        $('.async-'+id).replaceWith(data);
                        element.removeClass('unread loader');
                        $('.primaryunfold-'+id).slideDown(300);
                    }
            });
        }); ". 
      // Отправка форм аяксом :
    "
    $('body').on(
            'click',
            '.ajaxSubmit',
            function(){
                    form = $(this).parents('form');
                    jQuery.ajax({
                    'type':'POST',
                    'url':'/".$_GET['language']."/messages/reply',
                    'cache':false,
                    'data':form.serialize(),
                    'success':function(html){
                            id = form.parents('.thread').attr('id').substring(2);
                            $('#async-'+id).replaceWith(html);
                            $('#reset-'+id).click();
                            $('.form-'+id+' textarea').blur();
                            //form.children('textarea').blur();
                            }
                    });
            return false;
            }
    );", CClientscript::POS_READY);
    ?>
    <script type="text/javascript">
    </script>
    <div class="cnt left" >

        <h3 class="inline"><?=(empty($this->filter))? Yii::t('messages', 'Все сообщения') : Yii::t('messages', $this->filter->title) ?></h3>
        <div class="form inline right">
            <div class="row newmessagebuttons buttons inline">
                <?= CHtml::beginForm(Yii::app()->createUrl('/messages/create')) ?>
                <?= CHtml::submitButton(Yii::t('messages', 'Новое сообщение'), array('class' => 'greybutton newmessagebutton')); ?>
                <?= CHtml::endForm() ?>
            </div>
        </div>
        <p class="separator"/>
    <?php } 
    else {
    ?>
        <div class="firstload">
    <?php } ?>
    <?php
    /* Структура сообщений::
     * 
     * Заголовок, АЯКС-ссылка, Ссылка развертывания контейнера
     * --------------
     * Развертываемый контейнер
     * --------------
     * Аякс-контейнер
     * --------------
     * Форма ответа
     * --------------
     */
    ?>    
    <?= (count($threads) == 0)? '<p>'.Yii::t('messages', 'Нет сообщений').'</p>' : '' ?>

    <?php foreach ($threads as $kthread => $thread) { ?>
        <div class="thread" id="t-<?= $thread->ID ?>">
            <a class="messagetitle unfolder loader <?= ($thread->msg_unread_count > 0 ) ? 'unread' : '' ?>" href="javascript:" id="<?= $thread->ID ?>">
                <?= Yii::t('answers', $thread->title) ?>
            </a>
            <div class="messagecount right shadowed curr"><?= $thread->msg_count ?></div>

            <div class="foldable">
                <div class="preloader async-<?= $thread->ID ?>">

                </div>

<?php
/*
 * Форма ответа
 */
?>                 
                <div class="primaryunfold primaryunfold-<?=$thread->ID?>">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'msg-messages-index-' . $thread->ID,
                        'enableClientValidation' => true,
                        'action' => ''
                            ));
                    ?>
                    <?= $form->errorSummary($replymessage); ?>
                    <div class="replyform form-<?=$thread->ID?>">
                        <div class="tail"></div>
                        <div class="shadowed formcontainer">

                            <div class="form">
                                <div class="row rel-container replymessagetext">
									<?= $form->hiddenField($replymessage, 'title', array('value' => $thread->title))?>
                                    <?= $form->textArea($replymessage, 'text', array('class' => 'overlayed')); ?>
                                    <?= $form->label($replymessage, 'text', array('class' => 'label-overlay')); ?>
                                    <?= $form->hiddenField($replymessage, 'thread_id', array('value' => $thread->ID)) ?>

                                    <?= $form->hiddenField($replymessage, 'lastupdate', array('value' => $thread->msg_max_id, 'class'=> 'last-' . $thread->ID)) ?>
                                </div>
                                <div class="row buttons replymessagebuttons">
                                    <?=CHtml::submitButton(Yii::t('messages', 'Отправить'), array('class'=>'ajaxSubmit greybutton'))?>
                                    <?= CHtml::resetButton('',array('class'=>'hiddenreset', 'id'=>'reset-' . $thread->ID))?>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php $this->endWidget(); ?> 
                <?php // Конец формы ответа?>                    
<?php if ($this->action->id <> 'reply') {?>
        <div class="close rel-container">
            <a class="close-text" href="javascript:"><?= Yii::t('messages', 'Закрыть') ?></a>
            <p class="separator close-text-underline"/>
        </div>
<?php }?>
            </div> <!-- primaryunfold -->
            </div>
        </div>


    <?
    }

    /*
     * Ссылка загрузки последующих писем
     */
    ?>
    <?php if ($loadmore) { ?>
        <div class="thread_async">  
            <p class="separator"/>
            <div class="loadmore-container rel-container">
            
                <?=
                CHtml::ajaxLink(
                        Yii::t('messages', 'Показать еще'), Yii::app()->createUrl('/messages/index', array('page' => $page + 1)), array('replace' => '.thread_async'), array('class' => 'loadmore rel-container', 'id'=> uniqid())
                )
                ?>
            </div>
        </div>
<?php } ?>
    <?php if (!Yii::app()->request->isAjaxRequest) { ?>
    </div>
    <?php } else {?>
    </div>
<script type="text/javascript" >
    $('.firstload').slideDown(300, function() { $(this).removeClass('firstload');});    
</script>    
    <?php }?>