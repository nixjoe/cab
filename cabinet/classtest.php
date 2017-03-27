<?php

 class MerchantFabric 
{
// Класс, который будет подгружать реализацию для конкретной платежной системы и способа оплаты

    public static function buildMerchant($system, $method = Null)
    {
        if (class_exists($system) && // Класс существует
            in_array('Merchant', class_parents($system)) // Класс реализует Мерчант
            ) {
            return (new $system($method));
        }
    }

}

 abstract class Merchant
{
// Абстрактный класс-продукт мерчанта.
	
    public $config = array(); // Конфиг
    public $payment = array(); // Платеж
    
    public function __construct ($method = Null)
    {
        if (!empty($method)) {
            // Способ оплаты не указан - используем только конфиг платежной системы
            $this->config = Yii::app()->params['paysystems'][get_class($this)];
            
        } 
        else 
            {
            // Способ оплаты указан - используем объединенный конфиг П.С. и способа платежа
            $this->config = array_merge(
                Yii::app()->params['paysystems'][get_class($this)],
                Yii::app()->params['paysystems'][get_class($this)]['methods'][$method]
            );
            
        }
    }

    // Генерация атрибутов для формы запроса платежа перед передачей на сайт П.С.
    public abstract function createForm (object $payment, object $user=Null);

    // Обработка пришедшего запроса. 
    // Должна включать: 
    // 1. валидацию (проверку подписи), $this->validate();
    // 2. сопоставление атрибутов запроса с типовыми атрибутами, 
    // 3. проверку значений атрибутов с сохраненными в бд, $this->checkinfo();
    // Может включать: изменение статуса платежа, проведение платежа
    public abstract function processResponse ($response);

    // Обработка запроса на страницу успешного платежа
    // Должна включать преобразование пришедшей информации в атрибуты для страницы успешного платежа.
    public abstract function createSuccess ($response);

    // Обраобтка запроса на страницу неудачного платежа
    // Должна включать преобразование пришедшей информации в атрибуты для страницы неудачного платежа
    public abstract function createFail ($response);

    // Проверка подписи платежа
    abstract function validate ();

    // Проверка информации о платеже
    function checkinfo ()
    {
        // Выборка информации о платеже из бд. 
        // Сравнение атрибутов пришедшего платежа с сохраненными в базе. 
        // Проверка, что платеж еще небыл проведен.
    }

    public function perform ()
    {
        // Совершение платежа. Перед запуском обязательно выполнение валидации и проверки инфо. 
        // Возможно, стоит создать атрибуты, которые будут устанавливаться при выполнении соответствующих методов.
        // 1. Пробуем провести платеж на торговом сервере
        // 2. если платеж удался - обновляем статусы, прописываем информацию о плательщике в базу.
    }

}

class webmoney extends Merchant {
    
    public function __construct($method = Null) {
        parent::__construct($method);
    }
    
    // Реализация абстрактных методов:

    public function createForm($payment, $user = Null) {
        echo "create form for webmoney";
        var_dump($payment);
        var_dump($user);
    }
    
    public function processResponse($response) {
        echo "process response of webmoney";
        var_dump($response);
    }
    
    public function createSuccess($response) {
        echo "success response of webmoney";
        var_dump($response);
    }
    
    public function createFail($response) {
        echo "fail response of webmoney";
        var_dump($response);
    }
    
    public function validate() {
        echo "validate of webmoney";
    }
}

?>