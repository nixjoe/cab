<?php
/**
 * Created by PhpStorm.
 * User: mike (mikelmi84@gmail.com)
 * Date: 01.11.13
 * Time: 20:19
 */

class MQApi {

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array|object
     */
    private $config = array(
        'host' => null,
        'post' => 80,
        'timeout' => 5,
        'cache' => false,
        'cacheTime' => 5,
    );

    /**
     * @var bool
     */
    private $_cacheable = false;

    /**
     * @param array $params
     * @throws CException
     */
    public function __construct(array $params = null) {
        $config = $params ? $params : Yii::app()->params['mqApi'];
        if (is_array($config)) {
            $this->config = array_merge($this->config, $config);
        }

        if (!$this->config['host']) {
            throw new CException('MetaTrader Server is not defined');
        }

        $this->config = (object) $this->config;
        $this->_cacheable = $this->config->cache && (Yii::app()->cache instanceof CCache);
    }

    /**
     * @param string $query
     * @param array $params
     * @return string
     * @throws MQConnectException
     */
    private function _getQuery($query, array $params = null) {
        $res = '';
        $errno = null;
        $errstr = null;

        $ptr = @fsockopen($this->config->host, $this->config->port, $errno, $errstr, $this->config->timeout);

        if ($ptr) {
            if ($params) {
                $query = strtr($query, $params);
            }
            if (fputs($ptr, sprintf("W%s\r\nQUIT\r\n", $query)) !== FALSE) {
                while (!feof($ptr)) {
                    if (($line=fgets($ptr,128))=="end\r\n") break;
                    $res .= $line;
                }
            }
            fclose($ptr);

            return $res;

        } else {
            throw new MQConnectException($errstr, $errno);
        }
    }

    /**
     * @param string $query
     * @param array $params
     * @param bool $noCache
     * @return mixed|string
     */
    public function query($query, array $params = null, $noCache = false) {
        $result = '';

        $cacheable = !$noCache && $this->_cacheable;
        if ($cacheable) {
            $cacheId = $this->login.'_'.crc32($query.($params ? implode(',',$params) : ''));

            /** @var CFileCache $cahce */
            $cahce = Yii::app()->cache;

            $result = $cahce->get($cacheId);

            if ($result === false) {
                $result = $this->_getQuery($query, $params);
                Yii::app()->cache->set($cacheId, $result, $this->config->cacheTime);
            }
        } else {
            $result = $this->_getQuery($query, $params);
        }

        return $result;
    }

    /**
     * @param string $line
     * @return string mixed
     */
    public function getParam($line) {
        @list($tmp, $value) = explode(' ', $line);
        return $value;
    }

    /**
     * @param string $login
     * @param string $password
     * @return bool|string
     */
    public function login($login, $password) {
        $login = substr($login,0,14);
        $password = substr($password,0,16);

        $res = $this->query(sprintf('WAPUSER-%s|%s', $login, $password), null, true);

        if ($res == '!!!CAN\'T CONNECT!!!'
            || strpos($res,'Invalid') !== false
            || strpos($res,'Disabled') !== false) {
            return false;
        }

        $this->login = $login;
        $this->password = $password;

        return $res;
    }

    /**
     * @param mixed|null $from
     * @param mixed|null $to
     * @param bool $parse
     * @return array|string
     */
    public function getHistory($from = null, $to = null, $parse = true) {
        $query = 'USERHISTORY-login=:login|password=:password|from=:from|to=:to';

        $params = array(
            ':login' => $this->login,
            ':password' => $this->password,
            ':from' => $from,
            ':to' => $to,
        );

        $result = $this->query($query, $params);

        if (!$parse) {
            return $result;
        }

        $items = array();
        $lines = explode("\r\n", $result);
        $size = sizeof($lines);

        $row_keys = array('order', 'time', 'type', 'lots', 'symbol', 'price', 'sl', 'tp', 'close_time', 'price2', 'commission', 'swap', 'profit', 'taxes');

        for ($i=3; $i < $size; $i++) {
            if ($lines[$i]==='0') break;
            $row = explode("\t",$lines[$i]);
            if (strpos($row[2],'balance')!==false) {
                $row[3] = $row[13];
                $row[13] = '';
                $row[4] = $row[5] = $row[6] = $row[7] = $row[8] = $row[9] = $row[10] = $row[11] = '';
            } elseif (strpos($row[2],'limit')!==false){
                $row[10] = $row[11] = $row[12] = '';
            }
            $items[] = array_combine($row_keys, $row);
        }

        $profit_loss = @$this->getParam($lines[$i+6]);
        $deposit = @$this->getParam($lines[$i+1]);

        return array(
            'account' => @$lines[0],
            'name' => @$lines[1],
            'data' => $items,
            'profit_loss' => $profit_loss,
            'deposit' => $deposit,
            'credit' => @$this->getParam($lines[$i+3]),
            'withdrawal' => @$this->getParam($lines[$i+2]),
            'profit' => $deposit+$profit_loss
        );
    }

    public function getInfo($parse=true) {
        $query = 'USERINFO-login=:login|password=:password';

        $params = array(
            ':login' => $this->login,
            ':password' => $this->password,
        );

        $result = $this->query($query, $params);

        if (!$parse) {
            return $result;
        }

        $items = array();
        $lines = explode("\r\n", $result);
        $size = sizeof($lines);

        $row_keys = array('order', 'time', 'type', 'lots', 'symbol', 'price', 'sl', 'tp', 'price2', 'commission', 'swap', 'profit');
        $t_row_keys = $row_keys;
        $rk_size = sizeof($row_keys);

        $beginIndex = 3;
        $balance     = $this->getParam(@$lines[$beginIndex]);
        $equity      = $this->getParam(@$lines[$beginIndex+1]);
        $margin      = $this->getParam(@$lines[$beginIndex+2]);
        $free_margin = $this->getParam(@$lines[$beginIndex+3]);
        $margin_level= $margin!=0 ? number_format(100*($equity/$margin),2,'.','').'%' : '0%';

        for ($i=$beginIndex+4; $i < $size; $i++) {
            if ($lines[$i]==='0') break;
            $row = explode("\t",$lines[$i]);
            $rsize = sizeof($row);
            if ($rsize > $rk_size) {
                $row_keys = array_merge($row_keys, range($rk_size, $rsize-1));
                $rk_size = sizeof($row_keys);
            }
            $items[] = @array_combine($row_keys, $row);
        }

        $profit = $this->getParam(@$lines[$i+3])+$this->getParam(@$lines[$i+1])+$this->getParam(@$lines[$i+2]);

        $items_ext = array();
        $row_keys = array_slice($t_row_keys,0,9);
        $rk_size = sizeof($row_keys);

        for ($i+=4; $i < $size; $i++) {
            if ($lines[$i]==='0') break;
            $row = explode("\t",$lines[$i]);
            $rsize = sizeof($row);
            if ($rsize > $rk_size) {
                $row_keys = array_merge($row_keys, range($rk_size, $rsize-1));
                $rk_size = sizeof($row_keys);
            }
            $items_ext[] = @array_combine($row_keys, $row);
        }

        return array(
            'account' => @$lines[0],
            'name' => @$lines[1],
            'data' => $items,
            'data_ext' => $items_ext,
            'balance' => $balance,
            'equity' => $equity,
            'margin' => $margin,
            'free_margin' => $free_margin,
            'margin_level' => $margin_level,
            'profit' => $profit,
        );
    }

    public function getQuotes($quotes) {
        $q = is_array($quotes) ? implode(',', $quotes) : $quotes;
        if (substr($q, -1) !== ',') {
            $q .= ',';
        }

        $result = array();

        $res = $this->query('QUOTES-'.$q);
        $res = explode("\n",$res);

        foreach($res as $line) {
            if (isset($line[0])) {
                $tmp = explode(' ',$line);
                if ($tmp && isset($tmp[3])) {
                    $result[$tmp[1]] = array(
                        'symbol' => $tmp[1],
                        'bid' => $tmp[2],
                        'ask' => $tmp[3],
                    );
                }
            }
        }

        return $result;
    }
}

class MQConnectException extends CException {

}