<?php

class JustPaySuccessModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $justPay = new JustPay;

        $this->setTemplate($justPay->buildTemplatePath('success'));
    }
}