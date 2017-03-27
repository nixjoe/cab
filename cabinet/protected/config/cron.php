<?php
return [
    'basePath'   => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name'       => 'Cron Job',
    'preload'    => ['log'],
    'import'     => [
        'application.models.*',
        //'application.components.*',
        //'application.extensions.*'
    ],
    // application components
    'components' => [
        'db'  => [
            'initSQLs'         => ["set time_zone='+00:00';"],
            'connectionString' => 'mysql:host=localhost;dbname=fxprivat',
            'emulatePrepare'   => true,
            'username'         => 'root',
            'password'         => 'mysql',
            'charset'          => 'utf8',
        ],
        'log' => [
            'class'  => 'CLogRouter',
            'routes' => [
                [
                    'class'   => 'CFileLogRoute',
                    'logFile' => 'cron.log',
                    'levels'  => 'error, warning',
                ],
                [
                    'class'   => 'CFileLogRoute',
                    'logFile' => 'cron_trace.log',
                    'levels'  => 'trace',
                ],
            ],
        ],
    ],
];