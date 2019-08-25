<?php

class JustPayFinalizeModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();

        $this->setTemplate('finalize.tpl');
    }
}