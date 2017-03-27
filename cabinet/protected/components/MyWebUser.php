<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mike
 * Date: 22.10.13
 * Time: 12:39
 * To change this template use File | Settings | File Templates.
 */

class MyWebUser extends CWebUser{

    const _LK_COOKIE = '_lk_logged_in';

    protected function afterLogin($fromCookie) {
        parent::afterLogin($fromCookie);
        if (!Yii::app()->params['admin_side']) {
            $cookie = new CHttpCookie(self::_LK_COOKIE, 'Y');
            $cookie->domain = $this->getRootDomain();
            Yii::app()->request->cookies->add($cookie->name, $cookie);
        }
    }

    protected function afterLogout() {
        parent::afterLogout();
        Yii::app()->request->cookies->remove(self::_LK_COOKIE);
        unset(Yii::app()->request->cookies[self::_LK_COOKIE]);
        setcookie(self::_LK_COOKIE,null,0,'/',$this->getRootDomain());
    }

    protected function getRootDomain() {
        $hostname = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : @$_SERVER['SERVER_NAME'];
        if ($hostname) {
            return preg_replace('/^(www)|(my)\./i', '.', $hostname);
        }

        return '';
    }
}