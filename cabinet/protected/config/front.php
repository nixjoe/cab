<?php
return CMap::mergeArray(
    require_once(dirname(__FILE__) . '/main.php'),
    [
        // стандартный контроллер
        'defaultController' => 'account',
        // компоненты
        'components'        => [
            'user'       => [
                'allowAutoLogin' => false,
                'class'          => 'MyWebUser',
            ],
            'urlManager' => [
                'class'          => 'application.components.UrlManager',
                'urlFormat'      => 'path',
                'caseSensitive'  => 'false',
                'showScriptName' => false,
                'rules' => [
                    //                '/<pid:\w+>' => 'site/page',
                    '<language:([A-Za-z]{2})>/'                                           => 'account/index',
                    '<language:([A-Za-z]{2})>/<module:\w+>/<controller:\w+>'              => '<module>/<controller>',
                    '<language:([A-Za-z]{2})>/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
                    '<language:([A-Za-z]{2})>/<controller:\w+>/<id:\d+>'                  => '<controller>/view',
                    '<language:([A-Za-z]{2})>/<controller:\w+>/<action:\w+>/<id:\d+>'     => '<controller>/<action>',
                    '<language:([A-Za-z]{2})>/<controller:\w+>/<action:\w+>'              => '<controller>/<action>',
                    '<language:([A-Za-z]{2})>/register'                                   => 'auth/register',
                    '<language:([A-Za-z]{2})>/login'                                      => 'auth',
                    '<language:([A-Za-z]{2})>/restore'                                    => 'auth/restore',
                    '<language:([A-Za-z]{2})>/<controller:\w+>'                           => '<controller>',
                    '<language:([A-Za-z]{2})>/'                                           => '/',
                    /*

                                    '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                                    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                                    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                                    'register'=>'auth/register',
                                    '/login'=>'auth',
                                    */
                    //                            'site/page/<pid:\w+>'=>'/site/page/',
                    //                            'site/page/<pid:\w+>/<spid:.*>'=>'/site/page/'
                ],
            ],
        ],
    ]
);
?>
