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