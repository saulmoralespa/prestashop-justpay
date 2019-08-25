<?php

class JustPayErrorModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $justPay = new JustPay;

        if (Tools::getValue('idOrder'))
            $justPay->updateStatusPay(Tools::getValue('idOrder'), 'ERROR');

        $this->setTemplate('error.tpl');
    }
}