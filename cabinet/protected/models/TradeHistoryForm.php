<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 02.11.13
 * Time: 1:26
 */

class TradeHistoryForm extends CFormModel{

    public $account;
    public $password;
    public $fromDate;
    public $toDate;

    public $fromDateTxt;
    public $toDateTxt;

    /**
     * @var MQApi|null
     */
    private $_mqApi;

    public function rules() {
        return array(
            array('account, password', 'required'),
            array('password', 'authenticate'),
            array('fromDate, toDate', 'datetime'),
            array('fromDate, toDate', 'required'),

            array('account, password, fromDate, toDate, fromDateTxt, toDateTxt', 'safe'),
        );
    }

    public function attributeLabels() {
        return array(
            'account' => Yii::t('trade', 'Счет'),
            'password' => Yii::t('trade', 'Пароль инвестора'),
        );
    }

    public function authenticate($attribute,$params) {
        if(!$this->hasErrors()) {
            $result = false;
            if ($this->_mqApi && !empty($this->account) && !empty($this->password)) {
                try {
                    $result = $this->_mqApi->login($this->account, $this->password);
                } catch(MQConnectException $e) {
                    $this->addError("password", Yii::t('trade','Can\'t connect to server'));
                    Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'mqapi');
                    return;
                }
            }
            if (!$result) {
                $this->addError("password", Yii::t('trade','Пароль инвестора введен неправильно'));
            }
        }
    }

    public function datetime($attribute,$params) {
        if(!$this->hasErrors($attribute)) {
            $val = $this->$attribute;
            if (!empty($val) && !is_int($val) && !preg_match('/^\d+$/', $val)) {
                $time = strtotime($val);
                if ($time === false || $time === -1) {
                    $this->addError($attribute, Yii::t('trade','Неверный формат даты'));
                } else {
                    $this->$attribute = $time;
                }
            }
        }
    }

    /**
     * @param MQApi $api
     */
    public function setMQApi(MQApi $api) {
        $this->_mqApi = $api;
    }

    /**
     * @return MQApi|null
     */
    public function getMQApi() {
        return $this->_mqApi;
    }
} 