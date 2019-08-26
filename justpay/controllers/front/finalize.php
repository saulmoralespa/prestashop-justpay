<?php

class JustPayFinalizeModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $justPay = new JustPay;

        $this->setTemplate($justPay->buildTemplatePath('finalize'));
    }
}