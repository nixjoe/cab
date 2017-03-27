<?php

class FasaPay extends Merchant {
	public $payment = null;

    private $currencies = array('USD', 'IDR');

	public function __construct($method = null) {
        parent::__construct($method);
    }

    public function createSuccess() {

    }

    public function createFail() {

    }

    public function createForm($payment, $user=null) {

        $this->payment = $payment;

        $currency = Currencies::model()->findByPk($this->payment->currency);
        $currencyCode = $currency ? $currency->alphaCode : 'USD';

        if (!in_array($currencyCode, $this->currencies)) {
            $currencyCode = 'USD';
        }

        $data = array(
            'fp_acc' => $this->param('merchant_id'),
            'fp_store' => $this->param('fp_store'),
            'fp_item'  => $this->param('fp_item'),
            'fp_amnt' => $payment->amount,
            'fp_currency' => $currencyCode,
            'fp_fee_mode' => $this->param('fp_fee_mode', 'FsC'),
            'fp_success_url' => $this->param('fp_success_url'),
            'fp_success_method' => $this->param('fp_success_method', 'POST'),
            'fp_fail_url' => $this->param('fp_fail_url'),
            'fp_fail_method' => $this->param('fp_fail_method', 'POST'),
            'fp_status_url' => $this->param('fp_status_url'),
            'fp_status_method' => $this->param('fp_status_method', 'POST'),
            'fp_comments' => $this->param('fp_comments'),
            'fp_merchant_ref' => $this->payment->id,
            'F_ORDER_ID' => $this->payment->id,
            'F_TARGET_ID' => $this->payment->target_id,
        );

        return $data;
    }

    protected function param($key, $default='') {
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }

    public function validate(){
		self::log('validate fasapay');
        $result = false;
        if (isset($_POST['fp_paidto'], $_POST['fp_hash'])) {
            $post_hash = $_POST['fp_hash'];

            $msg = $_POST['fp_paidto'].':';
            $msg .= $_POST['fp_paidby'].':';
            $msg .= $_POST['fp_store'].':';
            $msg .= $_POST['fp_amnt'].':';
            $msg .= $_POST['fp_batchnumber'].':';
            $msg .= $_POST['fp_currency'].':';
            $msg .= $this->param('security_key');

            $hash = hash('sha256', $msg);

            $result = $hash === $post_hash && strtoupper($_POST['fp_paidto']) === $this->param('merchant_id');

            self::log($msg);
            self::log('Hashes', array('post_hash'=>$post_hash,'hash'=>$hash, 'store'=>$this->param('fp_store'), 'merchantId'=>$this->param('merchant_id')));
        }

        return $result;
    }

    public function setAttributes() {
        $this->orderId 	= $_POST['fp_merchant_ref'];
        $this->amount 	= $_POST['fp_amnt'];
		$this->payee 	= @$_POST['fp_paidby'];

        $payment = Payments::model()->findByPk($this->orderId);
        $this->status = self::STATUS_SUCCESS;

        $this->targetId 	= $payment->target_id;
        $this->currencyId 	= $payment->currency;
    }
}