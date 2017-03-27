<?php
return CMap::mergeArray(
    [
    // this is used in contact page
    'logoutTimer' => 15 * 60,
    'privatekey'  => '',//Этот ключ нужно перенести в localstorage/ssl, временно используется AES вместо RSA. 
    'publickey'   => '',
    'sms'         => [
        'user'     => '',
        'password' => ''
    ],
    'dir_upload_user' => 'C:/xampp/htdocs/cabinet/Upload/Users/',
    'smtp_serv'   => [
        'SMTPDebug'  => 1, // debugging: 1 = errors and messages, 2 = messages only  
        'SMTPAuth'   => true, // authentication enabled
        'SMTPSecure' => 'tls', // secure transfer enabled REQUIRED for GMail
        'server'     => 'smtp.gmail.com',
        'port'       => 587,
        'username'   => '',
        'fromName'   => 'Automatic notification',
        'from'       => '', //displayed as sender's email
        'sender'     => '', //reply-to field
        'password'   => '',
    ],
    'mqApi'       => [
        'host'      => '',
        'port'      => '',
        'cache'     => true,
        'cacheTime' => 5,
    ],
    'paysystems'  => [
        'Liqpay'         => [
            'merchant_id' => '',
            //идентификатор вашего мерчанта							//код основной валюты на вашем сайте (USD, RUR, UAH, EUR)
            'module_id'   => 1,                                    // Id модуля ПС	
            'signature'   => '',        // Подпись для остальных операций
            'server_url'  => 'https://my.fx-private.com/payment/payment/result/system/liqpay/method/',            //
            'result_url'  => 'https://my.fx-private.com/payment/payment/success/system/liqpay/method/',            //
            'fail_url'    => 'https://my.fx-private.com/payment/payment/fail/system/liqpay/method/',
            'submit_url'  => 'https://www.liqpay.com/?do=clickNbuy',
            'abbr'        => 'LP',
        ],
        'Okpay'          => [
            'ok_receiver' => '',                        //идентификатор вашего мерчанта
            'server_url'  => 'https://my.fx-private.com/payment/payment/result/system/okpay/method/',            //
            'result_url'  => 'https://my.fx-private.com/payment/payment/success/system/okpay/method/',            //
            'fail_url'    => 'https://my.fx-private.com/payment/payment/fail/system/okpay/method/',
            'submit_url'  => 'https://checkout.okpay.com/',
            'abbr'        => 'OK',
        ],
        'Webmoney'       => [
            'module_id'       => '2',
            'submit_url'      => 'https://merchant.webmoney.ru/lmi/payment.asp',
            'LMI_RESULT_URL'  => 'https://my.fx-private.com/payment/payment/result/system/webmoney/method',            //
            'LMI_SUCCESS_URL' => 'https://my.fx-private.com/payment/payment/success/system/webmoney/method',            //
            'LMI_FAIL_URL'    => 'https://my.fx-private.com/payment/payment/fail/system/webmoney/method',
            'wmz'             => '',
            'wme'             => '',
            'wmu'             => '',
            'wmr'             => '',
            'secret_key'      => '',
            'abbr'            => 'WM',
        ],
        'Libertyreserve' => [
            'module_id'     => '3',
            'submit_url'    => 'https://sci.libertyreserve.com',
            //'security_word' 	=> 'fxprivate2012',
            'security_word' => '',
            'lr_acc'        => '',
            'lr_store'      => '',
            'abbr'          => 'LR',
            'status_url'    => 'https://my.fx-private.com/payment/payment/result/system/libertyreserve/method',
            'cancel_return' => 'https://my.fx-private.com/payment/payment/fail/system/libertyreserve/method',
            'return_url'    => 'https://my.fx-private.com/payment/payment/success/system/libertyreserve/method',
            'method'        => 'POST'
        ],
        'Dixipay'        => [
            'module_id'         => '4',
            'submit_url'        => 'https://www.dixipay.com/order_api/index.php',
            'api_key'           => '',
            'recipient_account' => '',
            'recipient_name'    => 'FX Private',
            'abbr'              => 'DP',
        ],
        'Easypay'        => [
            'module_id'   => '5',
            'submit_url'  => 'https://merchant.easypay.ua/client/order',
            'merchant_id' => '',
            'secret_key ' => '',
            'abbr'        => 'EP',
        ],
        'Bank'           => [
            'module_id'  => '7',
            'submit_url' => '/profile/TransitBank',
        ],
        'rbk'            => [
            'submit_url' => 'https://rbkmoney.ru/acceptpurchase.aspx',
            'eshopId'    => '',
            'failUrl'    => 'https://my.fx-private.com/payment/payment/fail/system/rbk/method?',
            'successUrl' => 'https://my.fx-private.com/payment/payment/success/system/rbk/method?',
            'secretKey'  => 'Билецкая',
            'abbr'       => 'RK',
        ],
        'Moneybookers'   => [
            'module_id'     => '6',
            'submit_url'    => 'https://www.moneybookers.com/app/payment.pl',
            'status_url'    => 'https://my.fx-private.com/payment/payment/result/system/moneybookers/method/moneybookers',
            'cancel_return' => 'https://my.fx-private.com/payment/payment/fail/system/moneybookers/method/moneybookers',
            'return_url'    => 'https://my.fx-private.com/payment/payment/success/system/moneybookers/method/moneybookers',
            'pay_to_email'  => '',
            'secret'        => '',
            'abbr'          => 'MB',
        ],
        'Qiwi'           => [
            'module_id'  => '8',
            'submit_url' => 'https://w.qiwi.ru/setInetBill.do',
            'other_url'  => 'https://ishop.qiwi.ru/xml',
            'secret'     => '',
            'shopID'     => '',
            'abbr'       => 'QW'
        ],
        'Privat24'       => [
            'module_id'   => '9',
            'submit_url'  => 'https://api.privatbank.ua:9083/p24api/ishop',
            //'merchant_id' 		=> '69355',						//идентификатор вашего мерчанта		
            //'pass' 				=> 'gn8r4Cm67j9MKd27s5i9J3Avn9Fif0wI',		// Подпись для остальных операций
            'merchant_id' => '',                        //идентификатор вашего мерчанта
            'pass'        => '',        // Подпись для остальных операций
            'abbr'        => 'PB',
            'return_url'  => 'https://my.fx-private.com/payment/payment/success/system/privat24/method',
            'server_url'  => 'https://my.fx-private.com/payment/payment/result/system/privat24/method',
        ],
        'Dengionline'    => [
            'module_id'  => '10',
            'submit_url' => 'http://www.onlinedengi.ru/wmpaycheck.php',
            'project'    => '',                        //идентификатор вашего мерчанта
            'pass'       => '',        // Подпись для остальных операций
            'abbr'       => 'DO',
            'success'    => 'https://my.fx-private.com/payment/payment/success/system/dengionline/method',
            'fail'       => 'https://my.fx-private.com/payment/payment/fail/system/dengionline/method',
            'result'     => 'https://my.fx-private.com/payment/payment/result/system/dengionline/method',
        ],
        'PerfectMoney'   => [
            'merchant_id' => '',//идентификатор вашего мерчанта							//код основной валюты на вашем сайте (USD, RUR, UAH, EUR)
            'module_id'   => '12',                                    // Id модуля ПС	
            'signature'   => '',        // Подпись для остальных операций
            'server_url'  => 'https://my.fx-private.com/payment/payment/result/system/perfectmoney/method',            //
            'result_url'  => 'https://my.fx-private.com/payment/payment/success/system/perfectmoney/method',            //
            'fail_url'    => 'https://my.fx-private.com/payment/payment/fail/system/perfectmoney/method',
            'submit_url'  => 'https://perfectmoney.is/api/step1.asp',
            'abbr'        => 'PM',
        ],
        'FasaPay'        => [
            'module_id'      => '13',
            'submit_url'     => 'https://sci.fasapay.com/',
            'merchant_id'    => '',
            'fp_store'       => '',
            'security_key'   => '',
            'fp_fee_mode'    => 'FiR',
            'fp_status_url'  => 'https://my.fx-private.com/payment/payment/result/system/FasaPay/method',
            'fp_fail_url'    => 'https://my.fx-private.com/payment/payment/fail/system/FasaPay/method',
            'fp_success_url' => 'https://my.fx-private.com/payment/payment/success/system/FasaPay/method',
            'abbr'           => 'FP',
        ],
        'PayCo'          => [
            'module_id'   => '16',
            'merchant_id' => '',
            'mwallet'     => '',
            'separator'   => '',
            'secret'      => '',
            'submit_url'  => 'https://payments.pay.co/payments',
            'success'     => 'https://my.fx-private.com/payment/payment/success/system/PayCo/method',
            'fail'        => 'https://my.fx-private.com/payment/payment/fail/system/PayCo/method',
            'result'      => 'https://my.fx-private.com/payment/payment/result/system/PayCo/method',
            'abbr'        => 'PC',
        ],
        '_FasaPay_Test'  => [
            'module_id'      => '13',
            'submit_url'     => 'https://sandbox.fasapay.com/sci/',//'https://www.fasapay.com/sci/',
            'merchant_id'    => '',//'FP49543',
            'fp_store'       => 'Test Store',
            'security_key'   => '',
            'fp_status_url'  => 'https://my.fx-private.com/payment/payment/result/system/FasaPay',
            'fp_fail_url'    => 'https://my.fx-private.com/payment/payment/fail/system/FasaPay',
            'fp_success_url' => 'https://my.fx-private.com/payment/payment/success/system/FasaPay',
            'abbr'           => 'FP',
        ],
        'languages'      => ['ru' => 'Русский', 'ua' => 'Українська', 'en' => 'English'],
    ],
    'urlCountry'  => [
        'ua' => 'uk',
        'ru' => 'ru',
        'en' => 'en',
        'id' => 'id',
        'cn' => 'zh_cn',
        'es' => 'es',
        'ar' => 'ar',
        'my' => 'my',
    ],
],
require_once(dirname(__FILE__) . '/params-local.php'));
