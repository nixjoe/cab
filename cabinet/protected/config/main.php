<?php
// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return CMap::mergeArray(
    [
    'basePath'          => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name'              => 'Кабинет',
    'preload'           => [
        'log', // preloading 'log' component
        'bootstrap', // preload the bootstrap component
    ],
    'defaultController' => 'site',
    // autoloading model and component classes
    'import'            => [
        'application.models.*',
        'application.components.*',
        'application.extensions.*',
        'application.vendor.*'
    ],
    'modules'           => [
        // uncomment the following to enable the Gii tool
        'interkassa',
        'payment',
        'payout',
        'gii' => [
            'class'          => 'system.gii.GiiModule',
            'password'       => 'zaq12345',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'      => ['127.0.0.1', '::1'],
            'generatorPaths' => [
                'bootstrap.gii',
            ],
        ],
    ],
    // application components
    'components'        => [
        /*'request'=>array(
            'enableCookieValidation'=>false,
            'enableCsrfValidation'=>false,
        ),*/
        'user'           => [
            // enable cookie-based authentication
            'allowAutoLogin' => false,
            //'autoRenewCookie'=>false,
        ],
        'session'        => [
            'autoStart' => true,
            //'cookieMode' => 'none',
        ],
        'zip'            => [
            'class' => 'application.extensions.zip.EZip',
        ],
        'messages'       => [
            'class'            => 'CDbMessageSource',
            'forceTranslation' => true,
        ],
        'myButtonColumn' => [
            'class' => 'application.extensions.myButtonColumn'
        ],
        'ishop'          => [
            'class'   => 'ext.ishop.IShop',
            'options' => [
                'login'    => 204521,
                'password' => 'Jkminsy248471diesel12ljhjuf76'
            ]
        ],
        // uncomment the following to enable URLs in path-format
        /*
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),
        */
        'db'             => [
            'initSQLs'         => ["set time_zone='+00:00';"],
            'connectionString' => 'mysql:host=localhost;dbname=dbname',
            'emulatePrepare'   => true,
            'username'         => '',
            'password'         => '',
            'charset'          => 'utf8',
        ],
        'errorHandler'   => [
            'errorAction' => 'account/error',
        ],
        'log'            => [
            'class'  => 'CLogRouter',
            'routes' => [
                [
                    'class'  => 'CFileLogRoute',
                    'levels' => 'error, warning, info',
                ],
                [
                    'class'      => 'payment.components.PaymentLogRoute',
                    'levels'     => 'error, warning, info',
                    'categories' => 'payment.*',
                ],
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ],
        ],
    ],
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'            => require(dirname(__FILE__) . '/params.php'),
    // This is required for backend logic to function
    'behaviors'         => [
        'runEnd' => [
            'class' => 'application.components.WebApplicationEndBehavior',
        ],
    ],
    'sourceLanguage'    => 'ru',
    'language'          => 'ru',
],
require_once(dirname(__FILE__) . '/main-local.php'));
