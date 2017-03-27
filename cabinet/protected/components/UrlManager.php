<?php
class UrlManager extends CUrlManager
{
    public function createUrl($route,$params=array(),$ampersand='&')
    {
        if (!isset($params['language'])) {
            if (Yii::app()->user->hasState('language')) {
                $lang = Yii::app()->user->getState('language');
                if (intval($lang) > 0) {
                    Yii::app()->user->setState('language', 'ru');
                }
                Yii::app()->language = Yii::app()->user->getState('language');
            } else if(isset(Yii::app()->request->cookies['language']))
                Yii::app()->language = Yii::app()->request->cookies['language']->value;
                $k = array_search(Yii::app()->language,Yii::app()->params['urlCountry']);
            $params['language']= $k?$k:Yii::app()->language;
        }
        return parent::createUrl($route, $params, $ampersand);
    }
}
