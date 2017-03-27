<div id="footer-out">
    <div id="footer" class="footer-content">
        <div>
            <div class="left">
                <?php echo Yii::t('footer', 'Принимаем к оплате') ?>
            </div>
            <div class="right foot-icons">
                <?php echo $model->top_links ?>
            </div>
            <div class="clearfix"></div>
            <div class="block part">
                <?php echo $model->pay_systems ?>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="foot-menu-row">
            <?php echo $model->menu ?>
        </div>

        <div class="foot-soc-row">
            <div class="left">
                <div class="vcell">
                    <div class="cell-row"><?php echo Yii::t('footer', 'Мы в соц. сетях') ?></div>
                    <?php echo $model->soc_buttons ?>
                </div>
            </div>

            <div class="right copy-cell">
                <div class="cell-row">&copy; 2011-<?php echo date('Y'); ?>, All Rights Reserved.</div>
                <img src="/images/logoFooter.png" alt="FX-Private" align="right" />
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
</div>