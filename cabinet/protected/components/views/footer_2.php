<div id="footer-out">
    <div id="footer">
        <div class="left" id="footercopy">
            &copy; 2011-<?php echo date('Y'); ?>, All Rigts Reserved.<br/><br/>
            <p id="footer-logo" class="right"/>
            <br/>
        </div>
        <div class="left" id="footerlinks">
            <?=CHtml::link(Yii::t('main', 'Инструкция пользователя'),'/link.php?lang='. $_GET['language'].'&slug=user_guide', array('target' => '_blank'))?>
            <a href="<? Yii::t('links', 'http://www.fx-private.ru/fortraders/safety.html') ?>"><?= Yii::t('main', 'Советы по безопасности') ?></a>
            <a href="http://www.fx-private.com/files/fxprivate4setup.exe"><?= Yii::t('main', 'Скачать МТ4') ?></a><br>
            <?=CHtml::link(Yii::t('main', 'Перейти на сайт компании'),'http://www.fx-private.com')?>


        </div>
    </div>
</div>