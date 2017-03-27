<?php

return array (
    'components'=>array(
        'session' => array(
            'class' => 'CDbHttpSession',
            'timeout' => 60*60*24*365,
        ),
        'db'=>array(
            'connectionString' => 'sqlite:' . $_SERVER['DOCUMENT_ROOT'] . '/protected/runtime/session-1.1.11-dev.db',
            'emulatePrepare' => true,
            'charset' => 'utf8',
        ),
    ),
);