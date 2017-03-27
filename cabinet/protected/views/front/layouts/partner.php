<?php $this->beginContent('//layouts/cabinet'); ?>
<div class="left">
	<div class="faqmenu shadowed left">
        <a href="<?= $this->createUrl('/partner/index') ?>">
            <div class="menuitem <?= (!empty($this->filter) && ($this->filter == "index") ? 'active' : '')?>" >
                <div id="switch" class="text"><?= Yii::t('partner', 'PARTNER_ACCOUNT') ?></div>
            </div>
        </a>
		<a href="<?= $this->createUrl('/partner/profile') ?>">
            <div class="menuitem <?= (!empty($this->filter) && ($this->filter == "profile") ? 'active' : '')?>" >
                <div id="switch" class="text"><?= Yii::t('partner', 'Партнёрская ссылка') ?></div>
            </div>
		</a>
	
		<a href="<?= $this->createUrl('/partner/clients') ?>">
		<div class="menuitem <?= (!empty($this->filter) && ($this->filter == "clients") ? 'active' : '')?> ">
			<div class="text"><?= Yii::t('partner', 'Мои привлеченные клиенты') ?></div>
			<div class=""></div>
		</div>
		</a>
	
		<a href="<?= $this->createUrl('/partner/statistic') ?>">
		<div class="menuitem <?= (!empty($this->filter) && ($this->filter == "statistic") ? 'active' : '')?> ">
			<div class="text"><?= Yii::t('partner', 'Статистика – привлеченные клиенты') ?></div>
			<div class=""></div>
		</div>
		</a>

        <a href="<?= $this->createUrl('/partner/replenish') ?>">
            <div class="menuitem <?= (!empty($this->filter) && ($this->filter == "replenish") ? 'active' : '')?> ">
                <div class="text"><?= Yii::t('partner', 'REPLENISH_CLIENT_ACCOUNT') ?></div>
                <div class=""></div>
            </div>
        </a>

	</div>
</div>

	<?php echo $content; ?>
<?php $this->endContent(); ?>