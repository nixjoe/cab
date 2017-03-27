<?php
class FooterWidget extends CWidget {

    public $view = 'footer';

	public function run() {
        $model = Footer::model()->findByAttributes(array('language' => $this->getLangISO()));
        if (!$model) {
            $model = new Footer();
        }
		$this->render($this->view, array('model'=>$model));
	}

    private function getLangISO() {
        $lang = Yii::app()->language;
        if (!$lang) {
            return 'ru';
        }
        switch($lang) {
            case 'uk': $lang = 'ua'; break;
            case 'zh_cn': $lang ='cn'; break;
        }
        return $lang;
    }
}