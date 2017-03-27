<?php

abstract class Merchant
{
    // Абстрактный класс-продукт мерчанта.
    const STATUS_FAIL = 2;
    const STATUS_DEFAULT = 3;
    const STATUS_SUCCESS = 4;
    const STATUS_WAIT = 5;
    public $config = []; // Конфиг
    public $payment = []; // Платеж
    public $method = null;
    public $systemId = null;
    public $orderId = null;
    public $targetId = null;
    public $currencyId = null;
    public $payee = null;
    public $amount = null;
    public $status = null;

    public function __construct($method = null) {
        if ($method === null) {
            // Способ оплаты не указан - используем только конфиг платежной системы
            $this->config = Yii::app()->params['paysystems'][get_class($this)];
            $this->systemId = $this->config['module_id'];
        } else {
            // Способ оплаты указан - используем объединенный конфиг П.С. и способа платежа
            $methodConfig = isset(Yii::app()->params['paysystems'][get_class($this)]['methods'][$method]) ?
                Yii::app()->params['paysystems'][get_class($this)]['methods'][$method] : [];
            $this->config = array_merge(
                Yii::app()->params['paysystems'][get_class($this)],
                $methodConfig
            );
            $this->method = $method;
        }
    }

    // Генерация атрибутов для формы запроса платежа перед передачей на сайт П.С.
    public abstract function createForm($payment);

    // Обработка запроса на страницу успешного платежа
    // Должна включать преобразование пришедшей информации в атрибуты для страницы успешного платежа.
    public abstract function createSuccess();

    // Обраобтка запроса на страницу неудачного платежа
    // Должна включать преобразование пришедшей информации в атрибуты для страницы неудачного платежа
    public abstract function createFail();

    // Проверка подписи платежа
    abstract function validate();

    // Установка атрибутов
    abstract function setAttributes();

    // Проверка информации о платеже
    function checkinfo() {
        self::log('checkinfo: orderId:', $this->orderId);
        $payment = Payments::model()->findByPk($this->orderId);
        self::log('checkinfo: paymentId:', $payment ? $payment->id : 'FAIL');
        ob_start();
        echo "order_id: [{$payment->id}] : [{$this->orderId}] \n";
        echo "amount: [{$payment->amount}] : [{$this->amount}] \n";
        echo "currency: [{$payment->currency}] : [{$this->currencyId}] \n";
        echo "payment_status: [{$payment->status}] : [3] \n";
        echo "this_status: [{$this->status}] : [4] \n";
        $content = ob_get_contents();
        ob_end_clean();
        $model = new PayLog();
        $model->content = $content;
        $model->save();
        if ($payment
            && $payment->id == $this->orderId
            && round($payment->amount, 2) == round($this->amount, 2)
            && $payment->currency == $this->currencyId
            && $payment->status == self::STATUS_DEFAULT
        ) {
            $model = new PayLog();
            $model->content = "checkinfo ok \n";
            $model->save();
            self::log('checkinfo SUCCESS');

            return true;
        } else {
            $model = new PayLog();
            $model->content = "checkinfo error \n";
            $model->save();
            self::log('checkinfo FAIL');

            return false;
        }
    }

    // Обработка пришедшего запроса.
    // Должна включать:
    // 1. валидацию (проверку подписи), $this->validate();
    // 2. сопоставление атрибутов запроса с типовыми атрибутами,
    // 3. проверку значений атрибутов с сохраненными в бд, $this->checkinfo();
    // Может включать: изменение статуса платежа, проведение платежа
    public function processResponse() {
        self::log('Start processResponse: ', $_REQUEST);
        if ($this->validate()) {
            self::log('validation success');
            $this->setAttributes();
            if ($this->checkinfo()) {
                $this->perform();
            }
        } else {
            self::log('validation fail');
        }
        self::log('End ProcessResponse');
    }

    protected static function log($msg, $data = null) {
        $log = $msg;
        if ($data !== null) {
            $log .= ' ' . print_r($data, true);
        }
        Yii::log($log, 'info', 'payment.' . get_called_class());
        Yii::getLogger()->flush(true);
    }

    public function perform() {
        self::log('start perform');
        // Совершение платежа. Перед запуском обязательно выполнение валидации и проверки инфо.
        // Возможно, стоит создать атрибуты, которые будут устанавливаться при выполнении соответствующих методов.
        // 1. Пробуем провести платеж на торговом сервере
        // 2. если платеж удался - обновляем статусы, прописываем информацию о плательщике в базу.
        // error_log("perform \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
        $payment = Payments::model()->findByPk($this->orderId);
        self::log('perform', ['payment' => @$payment->id, 'status' => $this->status]);
        //Если пришло уведомление о неудачном платеже - запишем статус в БД:
        if ($this->status == self::STATUS_FAIL) {
            $payment->status = self::STATUS_FAIL;
            $model = new PayLog();
            $model->content = "perform 1 \n";
            $model->save();
            // Иначе если уведомление о платеже ище не прихлдило:
        } elseif ($payment->status == self::STATUS_DEFAULT) {
            $model = new PayLog();
            $model->content = "perform 2 \n";
            $model->save();
            self::log('perform status default');
            //$mt = new MTconnector('78.140.130.240:443', 11, 'qwerty1');
            $mqApi = Yii::app()->params['mqApi'];
            $mt = new MTconnector($mqApi['host'] . '.' . $mqApi['port'], $mqApi['login'], $mqApi['password']);
            if ($mt->connected()) {
                self::log('perform MT connected');
                $model = new PayLog();
                $model->content = "perform 3 \n";
                $model->save();
                $rate = 1;
                $currency = Currencies::model()->findByPk($this->currencyId);
                $currencyFrom = $currency->alphaCode;
                self::log('perfrom currencyFrom=', $currencyFrom);
                if ($currencyFrom != 'USD') {
                    $model = new PayLog();
                    $model->content = "perform 4 \n";
                    $model->save();
                    $currMt = new MTconnector();
                    $currencyTo = 'USD';
                    $rates = $currMt->rates([$currencyFrom, $currencyTo], false);
                    if (!empty($rates["{$currencyFrom}{$currencyTo}"])) {
                        $rate = (float)$rates["{$currencyFrom}{$currencyTo}"];
                        echo "rate = {$rate} \n";
                    } else {
                        echo "{$currencyFrom}{$currencyTo} - Данная валютная пара не задана на сервере \n";
                        self::log('perfrom Fail: Данная валютная пара не задана на сервере');

                        return;
                    }
                    $model = new PayLog();
                    $model->content = "perform 5 \n";
                    $model->save();
                }
                $commentAmount = $this->amount;
                $amount = round($rate * $this->amount, 2);
                $amount2 = $this->amount;
                if ($this->systemId == 1) {
                    $currencyFrom = 'UAH';
                }
                if ($this->systemId == 5) {
                    $currencyFrom = 'UAH';
                }
                if ($this->systemId == 4) {
                    $currencyFrom = 'USD';
                }
                if ($this->systemId == 1) {
                    $paymentComment = Payments::model()->findByPk($this->orderId);
                    $currencyComment = Currencies::model()->findByPk($paymentComment->currency);
                    $currencyFrom = $currencyComment->alphaCode;
                }
                if (isset($payment->id)) {
                    $connection = Yii::app()->db;
                    ///////// добавляем в базу если транзитный счёт
                    $sql = 'SELECT user_id FROM payments WHERE id = \'' . $payment->id . '\' ';
                    $user = $connection->createCommand($sql)->queryRow();
                    self::log('user', $user);
                    if (isset($user['user_id'])) {
                        $user = $user['user_id'];
                        $sql = 'SELECT * FROM `transitaccounts` WHERE userID = \'' . $user . '\' AND currency = \'' . $payment->currency . '\' ';
                        $req = $connection->createCommand($sql)->queryRow();
                        $sql = 'SELECT transitID as tid FROM `users` WHERE ID = \'' . $user . '\'  ';
                        $tid = $connection->createCommand($sql)->queryRow();
                        if (isset($tid['tid']) && is_array($req) && count($req) > 0) {
                            if ($tid['tid'] == $payment->target_id) {
                                self::log('update transitacc', $amount2);
                                $sql = 'UPDATE `transitaccounts` SET amount = amount + \'' . floatval($amount2) . '\' WHERE userID = \'' . $user
                                    . '\' AND currency = \'' . $payment->currency . '\'';
                                $connection->createCommand($sql)->execute();
                            }
                        }
                    }
                    /////// умножаем на сто если центовый
                    $cent = 0;
                    //error_log("line amount ".$amount." \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
                    if (isset($payment->target_id)) {
                        $tradeaccounts = Tradeaccounts::model()->with('fxType_')->findAll(
                            ['condition' => 'mtID=' . $payment->target_id]
                        );
                        self::log('lineamount', $payment->target_id);
                        if (isset($tradeaccounts)) {
                            //error_log("line 3 \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
                            foreach ($tradeaccounts as $key => $val) {
                                if ($val->fxType_['name'] == 'FXCent') {
                                    $cent = 1;
                                    //error_log("line 4 \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
                                }
                            }
                        }
                    }
                    if ($cent == 1) {
                        $amount = $amount * 100;
                    }
                    //error_log("line amount ".$amount." \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
                    //error_log("line end \r\n", 3, "C:/xampp/htdocs/cabinet/test.txt");
                }
                ////////////
                self::log('start mt transaction');
                if ($mt->transaction(
                    (int)$this->targetId,
                    floatval($amount),
                    "D{$this->config['abbr']}-{$commentAmount}{$currencyFrom}"
                )
                ) {
                    $payment->status = self::STATUS_SUCCESS;
                    //$iklog->process_status = "All checks passed and transactions completed";
                    self::log('MT transaction success');
                    $model = new PayLog();
                    $model->content = "perform 6 \n";
                    $model->save();
                } else {
                    $payment->status = self::STATUS_FAIL;
                    //$iklog->process_status = "Connected to mt4 server, though transaction failed.";
                    //iklog->save();
                    //throw new CHttpException(400,'Connected to transaction server, though transaction failed.');
                    self::log('MT transaction fail: ', [
                        (int)$this->targetId,
                        floatval($amount),
                        "D{$this->config['abbr']}-{$commentAmount}{$currencyFrom}"
                    ]);
                    $model = new PayLog();
                    $model->content = "perform 7 \n";
                    $model->save();
                }
            } else {
                self::log('MT connection failed');
                $model = new PayLog();
                $model->content = "perform 8 \n";
                $model->save();
                $payment->status = self::STATUS_FAIL;
                //$iklog->process_status = "Could not connect to MT4";
                //throw new CHttpException(400,'Could not connect to transaction server.');
            }
        }
        $transfer = ($payment->transfer_id) ?
            Transfers::model()->findByPk($payment->transfer_id) :
            new Transfers();
        $transfer->issuer = $payment->user_id;
        $transfer->date = new CDbExpression('now()');
        $transfer->amount = $payment->amount;
        $transfer->actualamount = ($payment->status == self::STATUS_SUCCESS) ? $payment->amount : 0;
        $transfer->comission = 0;
        $transfer->currency = $payment->currency;
        $transfer->sourceID = 0;
        $transfer->targetID = $payment->target_id;
        $transfer->status = 0;
        $transfer->type = 0;
        $result = $transfer->save();
        self::log('Transfer save: ', $result ? 'TRUE' : 'FALSE');
        $payment->payee = $this->payee;
        $payment->paydate = new CDbExpression('now()');
        $payment->transfer_id = $transfer->ID;
        $result = $payment->save();
        self::log('Payment save: ', $result ? 'TRUE' : 'FALSE');
        $model = new PayLog();
        $model->content = "perform 9 \n";
        $model->save();
        if (isset($_GET['order'])) {
            header("Location: https://my.fx-private.com/payment/payment/success/system/qiwi/method/");
        }
    }
}