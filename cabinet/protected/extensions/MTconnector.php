<?php
/*
 * В данном файле будет описание 2х классов: MTconnector и MTrecord;
 * @version: 1
 */

class MTconnector {
    
    
    public $id;
    private $host;

    private $_queryApi;

    // Constructor initializes connection to MT4:
    public function __construct($host = NULL, $login = NULL, $password = NULL) {
        if (function_exists('mt4_Connect')) {
            // if configuration passed through parameters
            if ($host !=NULL && $login !=NULL && $password !=NULL ){
                
                //Получим идентификатор текущего соединения:
                $this->id = mt4_connect($host, $login, $password);
                
                //Запишем хост для последующего использования
                $this->host = $host;
                
            } else { // assume configuration is set in ini file:
                
                //Получим идентификатор текущего соединения:
                $this->id = mt4_connect();
                
                //Прочитаем хост из ини файла:
                $this->host = ini_get('mt4_wrapper.host');
            }    
        }
        
        else $this->id = NULL;
    }

    // Method to check if we are connected, since $id is private
    public function connected() {
        return ($this->id != 0);
    }
    
    // Destructor handles memory clean-up:
    public function __destruct() {
        // Check if we are even connected and disconnect if we are. 
        // This is important, otherwise memory will not be cleaned up.
        if ($this->id) mt4_Disconnect($this->id);
        $this->id = null;
    }
    
    // Query a user record from server:
    public function find($mtid) {
        // The actual array that will be sent as request:
        $request = array();
        
        // Check if array parameter has been supplied, if not - replace it with array.
        if (is_array($mtid)) {
            foreach ($mtid as $k=>$v) {
                $request[] = intval($v);
            }
        } else
            $request = array(intval($mtid));
        
        // Query 
        $response = mt4_UserRecordsRequest ($this->id, $request);

        // If user had requested just one record, return only the record needed;
        if (!is_array($mtid)) { 
            return $response[$mtid];
        }
        
        // Otherwise, return all records in associated array
        else {
            $return = array();
            foreach ($response as $k=>$v) {
                $return[$v['login']] = $v;;
            }
            return ($return);
        }
    }

    
// Универсальный метод, который сам определеяет, нужно обновить или создать запись:
    public function save($mtrecord) {
        if ( gettype($mtrecord) !== 'array' && gettype($mtrecord)!== 'MTrecord' ) {
            throw new Exception('Incorrect record type');
            return (false);
        }
        if (empty ($mtrecord['id'])) {
            $this->recordNew($mtrecord);
            return(true);
        }
        elseif ($mtrecord['id']) {
            $this->recordUpdate($mtrecord);
            return(true);
        }
    }

    // Update record function
    public function recordUpdate($mtrecord) {
        try {
            mt4_UserRecordUpdate($this->id, $mtrecord);
        } catch (Exception $e) {echo $e;};
    }

    // Create a new record
    public function recordNew($mtrecord) {
        try {
            $id = mt4_UserRecordNew($this->id, $mtrecord);
        } catch (Exception $e){echo $e;};
        return ($id);
    }
    
    public function transaction($account, $amount, $comment) {
        $info = array(
            'type' => "TT_BR_BALANCE",
            'cmd' => "OP_BALANCE",
            'orderby' => intval($account),
            'price' => floatval($amount),
            'comment' => $comment
        );
        
        try{
            mt4_TradeTransaction($this->id, $info);
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }
        
        return $result;
    }

    public function rates (array $currencies, $round=true, &$ask = null) {
        // @TODO: когда будут кешироваться символы (см ниже) - добавить статический
        // метод с дополнительным параметром - именем/адресом хоста
        // Запрос настроек символов с торгового сервера. В идеале - должен кешироваться.
        $q = mt4_SymbolsGetAll($this->id);

        // лимит для счетчиков
        $s = count ($currencies);
        
        // Инициализируем массив для запроса котировок по интересным символам
        $request = array();
        // внешний цикл - обход первой валюты в символах
        for ($i = 0; $i < $s; $i++) {
            // внутренний цикл - оптимизированный обход второй валюты в символах
            for ($k = $i+1; $k < $s; $k++) {
                
                // Проверка прямой послеодвательности валют
                if (isset($q[strtoupper($currencies[$i] . $currencies[$k])])) {
                    $request[] = strtoupper($currencies[$i] . $currencies[$k]);
                }
                // Проверка обратной послеодвательности валют
                if (isset($q[strtoupper($currencies[$k] . $currencies[$i])])) {
                    $request[] = strtoupper($currencies[$k] . $currencies[$i]);
                }
                
            }
        }

        $rates = array();
        $quotes = array();

        /**
         * for IDR - use Api functions (becouse of bug in MTConnector)
         */
        /*$_idr = array('USDIDR', 'IDRUSD');
        $idr = array_intersect($_idr, $request);
        $request = array_diff($request, $_idr);

        if ($request) {
            $quotes = mt4_QuotesGet ($request, $this->host);
        }

        if ($idr) {
            $quotes = array_merge($quotes, $this->getApi()->getQuotes($idr));
        }*/

        /** get quotes by API */
        if ($request) {
            $quotes = $this->getApi()->getQuotes($request);
        }

        foreach ($quotes as $k=>$v) {
            $K_ = strtoupper($k);
            $rates[$K_] = $quotes[$k]['bid'];
            $rate = 1 / $quotes[$k]['ask'];
            if ($round) {
                $rate = round($rate,4);
            }
            $rates[substr($K_,3,3) . substr($K_,0,3)] = $rate;
            if ($ask !== null) {
                $ask[substr($K_,3,3) . substr($K_,0,3)] = $quotes[$k]['ask'];
            }
        }

        return ($rates);
    }

    /**
     * @return MQApi
     */
    public function getApi() {
        if (!$this->_queryApi) {
            $this->_queryApi = new MQApi();
        }
        return $this->_queryApi;
    }
}


//Класс ниже - нужно будет закончить для организации более красивой работы с записями с МТ4.


//class MTrecord implements ArrayAccess{
//    
//    public $attributes = array();
//    
//    public function __construct($MTArrayRecord = null) {
//        
//        // Создаем новую запись:
//        if ($MTArrayRecord == null) {
//            
//        }
//        // Создаем объект по записи 
//        else
//        {
//            
//        }
//        
//        $this->attributes = array(
//                        'login' => 0, // login
//                        'group', // group
//                        'password', // password
//                        'enable' => 1, // enable
//                        'enable_change_password' => 0, // allow to change password
//                        'enable_read_only' => 0, // allow to open/positions (TRUE-may not trade)
//                        'password_investor', // read-only mode password
//                        'password_phone', // => iconv("UTF-8", "cp1251", $user->phonePassword),                             // phone password
//                        'name', //iconv("UTF-8", "cp1251", "{$user->familyName} {$user->givenName} {$user->middleName}"), // name
//                        'country', // => $country->eng,					// country
//                        'city', // => iconv("UTF-8", "cp1251", $user->city),                                    // city
//                        'state', // => "",					// state
//                        'zipcode', // => $model->zipcode,                                  // zipcode
//                        'address', // => iconv("UTF-8", "cp1251", $user->address),                       // address
//                        'phone', // => iconv("UTF-8", "cp1251", $user->phone),                             // phone
//                        'email', // => iconv("UTF-8", "cp1251", $user->email),                             // email
//                        'comment', //  => "Trade account",                       // comment
//                        'id', // => "01-02-03-04-05",                               // SSN (IRD)
//                        'status', // => "online",                                   // status
//                        'regdate', // => 12345678,                                  // registration date
//                        //'lastdate' => 12345678,                                 // last coonection time
//                        'leverage', // => intval($tradeaccount->leverage),					// leverage
//                        //'agent_account' => 77777777,                            // agent account
//                        //'timestamp' => 12345678,                                // timestamp
//                        //'balance' => 1000.51,                                   // balance
//                        //'prevmonthbalance' => 0.0,				// previous month balance
//                        //'prevbalance' => 0.0,                                   // previous day balance
//                        //'credit' => 0.0,					// credit
//                        //'interestrate' => 0.0,					// accumulated interest rate
//                        //'taxes' => 10.2,					// taxes
//                        //'prevmonthequity' => 0.0,				// previous month equity
//                        //'prevequity' => 0.0,                                    // previous day equity
//                        //'publickey' => "PUBLIC_KEY",                            // public key
//                        //'send_reports' => 1,                                    // enable send reports by email
//                        //'api_data' => "testAPIdata"                             // for API usage
//    );
//        );
//    }
//    public function offsetSet($offset, $value) {
//        if (is_null($offset)) {
//            $this->container[] = $value;
//        } else {
//            $this->container[$offset] = $value;
//        }
//    }
//    public function offsetExists($offset) {
//        return isset($this->container[$offset]);
//    }
//    public function offsetUnset($offset) {
//        unset($this->container[$offset]);
//    }
//    public function offsetGet($offset) {
//        return isset($this->container[$offset]) ? $this->container[$offset] : null;
//    }    
//    
//    public $login = 0;
//    public $group;
//    public $password;
//    public $enable = 1;
//    public $enable_change_password = 0;
//    public $enable_read_only = 0;
//    public $password_investor;
//}

?>