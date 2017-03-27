<?php
/**
 * @package IShopServer
 */
class Response {
  public $updateBillResult;
}

class Param {
  public $login;
  public $password;
  public $txn;      
  public $status;
}

class IShopServer {
    public function updateBill($params) {
		ob_start ();
		echo 'QIWI-3-';
		print_r($params);
		$content = ob_get_contents();
		ob_end_clean ();
		$status = 0;
		$model = new PayLog();
		$model->content = $content;
		$model->save();	
		$txn = $params->txn;
		$password = $params->password;

		if($params->status == Yii::app()->ishop->checkBill($txn)->status) {
			if($params->status == 60) {
				$payment = Payments::model()->findByPk($txn);
				if($payment && $payment->status == 3) {
					$status = 0;
				} else {
					$status = 210;				
				}
			}
		} else {
			$status = 150;
		}
      $responce = new Response();
		$responce->updateBillResult = $status;
		return $responce;
    }
}