<?php

class JustPayNotifyModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        if (!Tools::getValue('amount') ||
            !Tools::getValue('channel') ||
            !Tools::getValue('currency') ||
            !Tools::getValue('signature') ||
            !Tools::getValue('time') ||
            !Tools::getValue('trans_ID'))
            return;

        $amount = Tools::getValue('amount');
        $time = Tools::getValue('time');
        $currency = Tools::getValue('currency');
        $trans_id = Tools::getValue('trans_ID');
        $channel = Tools::getValue('channel');
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
