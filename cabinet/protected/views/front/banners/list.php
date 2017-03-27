<?php
    $lang = Yii::app()->language;

    switch($lang) {
        case 'uk': $lang = 'ua'; break;
        case 'zh_cn': $lang ='cn'; break;
    }

    if (!$lang) {
        $lang = 'ru';
    }

    if (!property_exists($this, 'user') || $this->user) {
        $this->user = Users::model()->findByPk(Yii::app()->user->getId());
    }

    $items = Banners::model()->findAll(array(
        'order' => 'position asc',
        'condition' => 'language = \''.$lang.'\' AND (status = '.Banners::STATUS_ALL.' OR status = '. ($this->user->partner ? Banners::STATUS_PARTNER : Banners::STATUS_NOT_PARTNER) .')',
    ));
?>

<div class="bnrs">
    <?php foreach($items as $item):?>
        <div class="bn-block">
            <a href="<?php echo $item->url ?>">
                <?php echo Chtml::image($item->content) ?>
            </a>
        </div>
    <?php endforeach ?>
</div>