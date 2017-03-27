<?php

class PayCo extends Merchant
{
    public $payment = null;

    public function __construct($method = Null)
    {
        parent::__construct($method);
    }

    public function createSuccess() {}

    public function createFail() {}

    public function validate()
    {

        ob_start();
        echo 'PayCo';
        print_r($_REQUEST);
        $content = ob_get_contents();
        ob_end_clean();
        $model = new PayLog();
        $model->content = $content;
        $model->save();

        $SIGN = null;
        if (
            isset($_REQUEST['wallet']) &&
            isset($_REQUEST['amt']) &&
            isset($_REQUEST['curr']) &&
            isset($_REQUEST['tid']) &&
            isset($_REQUEST['desc'])
        ){
            $SIGN = hash('sha256',
                $this->config['merchant_id'] .
                $this->config['separator'] .
                sprintf("%1.2f", $_REQUEST['amt']) .
                $this->config['separator'] .
                $_REQUEST['curr'] .
                $this->config['separator'] .
                $_REQUEST['desc'] .
                $this->config['separator'] .
                $_REQUEST['tid'] .
                $this->config['separator'] .
                $this->config['mwallet'] .
                $this->config['separator'] .
                $this->config['secret']
            );
        }

        if(isset($_REQUEST['sign']) && $_REQUEST['sign'] == $SIGN && isset($_REQUEST['status']) && $_REQUEST['status'] == 1) {
            $model = new PayLog();
            $model->content = "Validation OK \n";
            $model->save();
            return true;
        } else {
            $model = new PayLog();
            $model->content = "Validation Error \n";
            $model->save();
            return false;
        }

    }

    public function createForm($payment, $user=null) {
        $this->payment = $payment;

        $currency = Currencies::model()->findByPk($this->payment->currency);
        $currencyCode = $currency ? $currency->alphaCode : 'USD';

        $desc = "Deposit Account No.:{$payment->target_id}";
        $TID = $this->payment->id;

        $SIGN = hash('sha256',
            $this->config['merchant_id'].
            $this->config['separator'].
            sprintf("%1.2f",$payment->amount).
            $this->config['separator'].
            $currencyCode.
            $this->config['separator'].
            $desc.
            $this->config['separator'].
            $TID.
            $this->config['separator'].
            $this->config['mwallet'].
            $this->config['separator'].
            $this->config['secret']
        );

        return array(
            'desc' 			=> $desc,
            'curr' 			=> $currencyCode,
            'amt' 			=> $payment->amount,
            'MID' 			=> $this->config['merchant_id'],
            'TID' 			=> $TID,
            'MWALLET' 		=> $this->config['mwallet'],
            'SIGN' 			=> $SIGN,
            'choice' 		=> isset($_REQUEST['itemID']) && $_REQUEST['itemID'] == 50 ? 0 : 1
        );
    }

    public function setAttributes() {

        $this->orderId 	=  $_REQUEST['tid'];
        $this->amount 	= $_REQUEST['amt'];
        $this->payee 	= null;

        $this->status 	= self::STATUS_SUCCESS;

        $payment = Payments::model()->findByPk($this->orderId);

        $this->targetId 	= $payment->target_id;
        $this->currencyId 	= $payment->currency;
    }
}