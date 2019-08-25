<?php

class JustPayNotifyModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        if (!$_REQUEST['amount'] ||
            !$_REQUEST['channel'] ||
            !$_REQUEST['currency'] ||
            !$_REQUEST['signature'] ||
            !$_REQUEST['time'] ||
            !$_REQUEST['trans_ID'])
            return;

        $amount = $_REQUEST['amount'];
        $time = $_REQUEST['time'];
        $currency = $_REQUEST['currency'];
        $trans_id = $_REQUEST['trans_ID'];
        $channel = $_REQUEST['channel'];
        $confirm_transid = $trans_id;

        $justPay = new JustPay;

        $data_sign = "$justPay->public_key$time$channel$amount$currency$trans_id$justPay->secure_key";
        $signature = hash('sha256', $data_sign);
        $response_confirm = "$justPay->public_key,$time,$channel,$amount,$currency,$trans_id,$confirm_transid,$signature";
        $trans_id = explode('_', $trans_id);
        $order_id = $trans_id[0];

        $justPay->updateStatusPay($order_id);

        die($response_confirm);
    }
}
