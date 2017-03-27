<?php
return CMap::mergeArray(
    require_once(dirname(__FILE__) . '/main.php'),
    [
        // стандартный контроллер
        'defaultController' => 'site',
        // компоненты
        'components'        => [
            'session'   => [
                'class'   => 'CDbHttpSession',
                'timeout' => 60 * 60 * 24 * 365,
            ],
            // пользователь
            'user'      => [
                'loginUrl' => ['/site/login'],
            ],
            'bootstrap' => [
                'class' => 'ext.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
            ],
            // mailer
            //  		'mailer'=>array(
            //    		'pathViews' => 'application.views.backend.email',
            //    		'pathLayouts' => 'application.views.email.backend.layouts'
            //  		),
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
        ],
        'params'            => [
            'admin_side' => 1,
        ]
    ]
);
?>
