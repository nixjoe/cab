<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 01.11.13
 * Time: 17:16
 */

class TradeController extends Controller{

    public function actionHistory() {
        $data = array();
        $footerData = array();
        $info = array();

        $model = new TradeHistoryForm();

        $tradeAccounts = Tradeaccounts::model()->with('fxType_')->findAll(
            array('condition'=>'userID='.Yii::app()->user->getId())
        );

        $accounts = array();
        foreach($tradeAccounts as $row) {
            $accounts[$row['mtID']] = $row->fxType_['name'] . ': '.$row['mtID'];
        }

        if (isset($_POST['TradeHistoryForm'])) {
            $mq = new MQApi();
            $model->setMQApi($mq);
            $model->attributes = $_POST['TradeHistoryForm'];
            if ($model->validate()) {
                $data = $mq->getHistory($model->fromDate, $model->toDate);
                if ($data) {
                    $footerData = array(
                        array(
                            'colspan'=>12,
                            'value' => sprintf('Profit/Loss: %s
                                                Credit: %s
                                                Deposit: %s
                                                Withdrawal: %s',
                                                $data['profit_loss'],
                                                $data['credit'],
                                                $data['deposit'],
                                                $data['withdrawal']
                                        )
                        ),
                        $data['profit']
                    );
                    $data = $data['data'];
                }
                $info = $mq->getInfo();
            } else {
                Yii::app()->user->setFlash('error', array(
                    'header'=>Yii::t('alert', "Необходимо исправить следующие ошибки:"),
                    'text'=>CHtml::errorSummary($model, '','')
                ));
            }
        } else {
            $now = time();
            $from = strtotime('-1 month');
            $model->fromDate = $from;
            $model->toDate = $now;
            $model->fromDateTxt = date('j.m.Y', $from);
            $model->toDateTxt = date('j.m.Y', $now);
        }

        $dataProvider = new CArrayDataProvider($data, array(
            'keyField' => 'order',
            'pagination' => false
        ));

        $params = array(
            'model' => $model,
            'accounts' => $accounts,
            'data' => $dataProvider,
            'footerData' => $footerData,
            'info' => $info,
        );

        if (isset($_GET['ajax'])) {
            $this->renderPartial('history', $params);
            return;
        }

        $this->render('history', $params);
    }
} 