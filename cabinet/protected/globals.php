<?php

if (!function_exists('yiiparam')) {
    function yiiparam($name, $default = null) {
        if ( isset(Yii::app()->params[$name]) )
            return Yii::app()->params[$name];
        else
            return $default;
    }
}