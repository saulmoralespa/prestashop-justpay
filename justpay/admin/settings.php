<?php

$justPayLink = new Link();
$logName = $justPayLink->getBaseLink().'modules/'.$this->name.'/logs/justpay_';
$logName .= Tools::encrypt(_PS_MODULE_DIR_.$this->name.'/logs/').'.log';
$urlNotify = Context::getContext()->link->getModuleLink('justpay', "notify");

$form['method'] = array(
    'form' => array(
        'legend'  => array(
            'title' => $this->l('Account Settings'),
            'icon'  => 'icon-cogs',
        ),
        'input'   => array(
            array(
                'type'    => 'switch',
                'label'   => $this->l('Live mode'),
                'name'    => 'JUSTPAY_LIVE_MODE',
                'is_bool' => true,
                'desc'    => $this->l('Use this module in live mode'),
                'values'  => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Enabled'),
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Disabled'),
                    )
                )
            ),
            array(
                'type'    => 'switch',
                'label'   => $this->l('Enable log'),
                'name'    => 'JUSTPAY_ENABLE_LOG',
                'is_bool' => true,
                'hint'    => $this->l('Please enable log only if you needed it, as the file may take large spaces.'),
                'desc'    => $this->l('Log file is available in the logs directory of the module (make sure the directory has correct permissions)').'<br/>' . $logName,
                'values'  => array(
                    array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                    ),
                    array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                    )
                )
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Handle payment method'),
                'name' => 'JUSTPAY_HANDLE_PAYMENT_METHOD',
                'options' => array(
                    'id' => 'id_option',
                    'name' => 'name',
                    'query' => array(
                        array(
                            'id_option' => 'redirection',
                            'name' => $this->l('Redirection')
                        ),
                        array(
                            'id_option' => 'lightbox',
                            'name' => $this->l('Lightbox')
                        )
                    )
                )
            ),
            array(
                'type' => 'title',
                'label' => $this->l('Notify URL'),
                'name' => '',
                'desc' => $urlNotify
            )
        ),
        'submit'  => array(
            'title' => $this->l('Save')
        )
    )
);

$form['sandbox'] = array(
    'form' => array(
        'legend' => array(
            'title' => $this->l('Test credentials'),
            'icon' => 'icon-cog'
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Test Public key'),
                'name' => 'JUSTPAY_PUBLIC_KEY_TEST'
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Test Secure key'),
                'name' => 'JUSTPAY_SECURE_KEY_TEST'
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Test endpoint URL'),
                'name' => 'JUSTPAY_ENDPOINT_URL_TEST'
            )
        ),
        'submit' => array(
            'title' => $this->l('Save')
        )
    )
);

$form['production'] = array(
    'form' => array(
        'legend' => array(
            'title' => $this->l('Production credentials'),
            'icon' => 'icon-cog'
        ),
        'input' => array(
            array(
                'type' => 'text',
                'label' => $this->l('Public key'),
                'name' => 'JUSTPAY_PUBLIC_KEY_PRODUCTION'
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Secure key'),
                'name' => 'JUSTPAY_SECURE_KEY_PRODUCTION'
            ),
            array(
                'type' => 'text',
                'label' => $this->l('Endpoint URL'),
                'name' => 'JUSTPAY_ENDPOINT_URL_PRODUCTION'
            )
        ),
        'submit' => array(
            'title' => $this->l('Save')
        )
    )
);

return $form;