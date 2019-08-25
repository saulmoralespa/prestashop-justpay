<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(dirname(__FILE__) . '/lib/JustPayOrderState.php');

class JustPay extends PaymentModule
{
    const ACCEPT_CURRENCIES_PAYMENT_ONLINE = ['CLP', 'PEN', 'USD'];

    const ACCEPT_CURRENCIES_PAYMENT_CASH = ['CLP', 'PEN', 'USD'];

    const ACCEPT_CURRENCIES_PAYMENT_CARDS = ['CLP'];

    public $public_key;
    public $secure_key;
    public $end_point_url;
    public $handle_payment;

    public function __construct()
    {
        $this->name = 'justpay';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->author = 'Saul Morales Pacheco';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        parent::__construct();

        $this->ps_versions_compliancy = array('min' => '1.6.0', 'max' => _PS_VERSION_);
        $this->displayName = $this->l('Just Pay');
        $this->description = $this->l('Just Pay Payment Gateway');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');

        $config = Configuration::getMultiple(array(
            'JUSTPAY_LIVE_MODE',
            'JUSTPAY_ENABLE_LOG',
            'JUSTPAY_PUBLIC_KEY_TEST',
            'JUSTPAY_SECURE_KEY_TEST',
            'JUSTPAY_ENDPOINT_URL_TEST',
            'JUSTPAY_PUBLIC_KEY_PRODUCTION',
            'JUSTPAY_SECURE_KEY_PRODUCTION',
            'JUSTPAY_ENDPOINT_URL_PRODUCTION',
            'JUSTPAY_HANDLE_PAYMENT_METHOD',
            'JUSTPAY_EXPIRATION_TIME')
        );

        if($config['JUSTPAY_LIVE_MODE']){
            $this->public_key = $config['JUSTPAY_PUBLIC_KEY_PRODUCTION'];
            $this->secure_key = $config['JUSTPAY_SECURE_KEY_PRODUCTION'];
            $this->end_point_url = $config['JUSTPAY_ENDPOINT_URL_PRODUCTION'];
        }else{
            $this->public_key = $config['JUSTPAY_PUBLIC_KEY_TEST'];
            $this->secure_key = $config['JUSTPAY_SECURE_KEY_TEST'];
            $this->end_point_url = $config['JUSTPAY_ENDPOINT_URL_TEST'];
        }

        $this->handle_payment = $config['JUSTPAY_HANDLE_PAYMENT_METHOD'];
        $this->expiration_time = $config['JUSTPAY_EXPIRATION_TIME'];

    }

    public function install()
    {

        JustPayOrderState::setup();

        if (extension_loaded('curl') == false) {
            $this->_errors[] = $this->l('You have to enable the cURL extension on your server to install this module');
            return false;
        }

        Db::getInstance()->Execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'justpay` (
				  `id_justpay` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
				) ENGINE='._MYSQL_ENGINE_.'  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;'
        );

        return parent::install() &&
            $this->_createHooks() &&
            Configuration::updateValue('JUSTPAY_LIVE_MODE', 0) &&
            Configuration::updateValue('JUSTPAY_ENABLE_LOG', 0) &&
            Configuration::updateValue('JUSTPAY_PUBLIC_KEY_TEST', '') &&
            Configuration::updateValue('JUSTPAY_SECURE_KEY_TEST', '') &&
            Configuration::updateValue('JUSTPAY_ENDPOINT_URL_TEST', '') &&
            Configuration::updateValue('JUSTPAY_PUBLIC_KEY_PRODUCTION', '') &&
            Configuration::updateValue('JUSTPAY_SECURE_KEY_PRODUCTION', '') &&
            Configuration::updateValue('JUSTPAY_ENDPOINT_URL_PRODUCTION', '') &&
            Configuration::updateValue('JUSTPAY_HANDLE_PAYMENT_METHOD', 'redirection') &&
            Configuration::updateValue('JUSTPAY_EXPIRATION_TIME', 120);
    }

    public function uninstall()
    {
        JustPayOrderState::remove();

        return parent::uninstall() &&
            Configuration::deleteByName('JUSTPAY_LIVE_MODE') &&
            Configuration::deleteByName('JUSTPAY_ENABLE_LOG') &&
            Configuration::deleteByName('JUSTPAY_PUBLIC_KEY_TEST') &&
            Configuration::deleteByName('JUSTPAY_SECURE_KEY_TEST') &&
            Configuration::deleteByName('JUSTPAY_ENDPOINT_URL_TEST') &&
            Configuration::deleteByName('JUSTPAY_PUBLIC_KEY_PRODUCTION') &&
            Configuration::deleteByName('JUSTPAY_SECURE_KEY_PRODUCTION') &&
            Configuration::deleteByName('JUSTPAY_ENDPOINT_URL_PRODUCTION') &&
            Configuration::deleteByName('JUSTPAY_HANDLE_PAYMENT_METHOD') &&
            Configuration::deleteByName('JUSTPAY_EXPIRATION_TIME');
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitJustPay'))
            $this->postProcess();

        return $this->renderForm();

    }

    protected function renderForm()
    {
        $form = require_once(dirname(__FILE__) . '/admin/settings.php');

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitJustPay';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->_getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($form);

    }

    protected function postProcess()
    {
        $form_values = $this->_getConfigFormValues();
        foreach (array_keys($form_values) as $key) {

            if($key === 'JUSTPAY_EXPIRATION_TIME' && Tools::getValue($key) < 30){
                Configuration::updateValue($key, 30);
            }else{
                Configuration::updateValue($key, Tools::getValue($key));
            }
        }
    }

    protected function _getConfigFormValues()
    {
        return array(
            'JUSTPAY_LIVE_MODE' => Configuration::get('JUSTPAY_LIVE_MODE'),
            'JUSTPAY_ENABLE_LOG' => Configuration::get('JUSTPAY_ENABLE_LOG'),
            'JUSTPAY_PUBLIC_KEY_TEST' => Configuration::get('JUSTPAY_PUBLIC_KEY_TEST'),
            'JUSTPAY_SECURE_KEY_TEST' => Configuration::get('JUSTPAY_SECURE_KEY_TEST'),
            'JUSTPAY_ENDPOINT_URL_TEST' => Configuration::get('JUSTPAY_ENDPOINT_URL_TEST'),
            'JUSTPAY_PUBLIC_KEY_PRODUCTION' => Configuration::get('JUSTPAY_PUBLIC_KEY_PRODUCTION'),
            'JUSTPAY_SECURE_KEY_PRODUCTION' => Configuration::get('JUSTPAY_SECURE_KEY_PRODUCTION'),
            'JUSTPAY_ENDPOINT_URL_PRODUCTION' => Configuration::get('JUSTPAY_ENDPOINT_URL_PRODUCTION'),
            'JUSTPAY_HANDLE_PAYMENT_METHOD' => Configuration::get('JUSTPAY_HANDLE_PAYMENT_METHOD'),
            'JUSTPAY_EXPIRATION_TIME' => Configuration::get('JUSTPAY_EXPIRATION_TIME')
        );
    }

    public function _createHooks()
    {
        $registerStatus = $this->registerHook('header') &&
            $this->registerHook('footer') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('paymentReturn');
        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
            $registerStatus &= $this->registerHook('payment');
        } else {
            $registerStatus &= $this->registerHook('paymentOptions');
        }
        return $registerStatus;
    }

    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'/views/css/iframe-lightbox.min.css', 'all');
    }

    public function hookFooter()
    {
        $this->context->controller->addJS($this->_path.'/views/js/lightbox.js', 'all');
    }

    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookPayment($params)
    {
        if (!$this->active ||
            empty($this->public_key) ||
            empty($this->secure_key) ||
            empty($this->end_point_url) ||
            !$this->checkCurrency($params['cart'])) return;

        $cart = $params['cart'];

        $currency = $this->getSingleCurrency($cart->id_currency);

        $this->smarty->assign(array(
            'imgOnline' => $this->_path . "views/img/online-$currency.png",
            'imgCash' => $this->_path . "views/img/cash-$currency.png",
            'imgCards' => $this->_path . "views/img/cards-$currency.png",
            'currency' => $currency,
            'currenciesOnline' => self::ACCEPT_CURRENCIES_PAYMENT_ONLINE,
            'currenciesCash' => self::ACCEPT_CURRENCIES_PAYMENT_CASH,
            'currenciesCards' => self::ACCEPT_CURRENCIES_PAYMENT_CARDS
        ));

        return $this->display(__FILE__, 'payment.tpl');
    }

    /**
     * @param $params
     * @return string|void
     */
    public function hookPaymentReturn($params)
    {
        if (!$this->active) return;

        global $smarty, $cart;

        $addressdelivery = new Address(intval($cart->id_address_delivery));

        if (version_compare(_PS_VERSION_, '1.7.0.0 ', '<')){
            $order = $params['objOrder'];
            $amount = $params['total_to_pay'];
        }else{
            $order = $params['order'];
            $amount = $params['order']->getOrdersTotalPaid();
        }

        try{
            $idOrder = Tools::getValue('id_order');
            $currency = $this->getSingleCurrency($order->id_currency);

            $time = date('Y-m-d\TH:i:s');
            $channel = Tools::getValue('payMethod');
            $transId = $idOrder . "_" . time();
            $urlOk = Context::getContext()->link->getModuleLink('justpay', "success");
            $urlError = Context::getContext()->link->getModuleLink('justpay', "error", ['idOrder' => $idOrder]);
            $urlFinalizar = Context::getContext()->link->getModuleLink('justpay', "finalize");

            $data_sign = "$this->public_key$time$amount$currency$transId$this->expiration_time$urlOk$urlError$channel$this->secure_key";
            $signature = hash('sha256', $data_sign);

            $data = [
                'public_key' => $this->public_key,
                'time' => $time,
                'channel' => $channel,
                'amount' => $amount,
                'currency' => $currency,
                'trans_id' => $transId,
                'time_expired' => $this->expiration_time,
                'url_ok' => $urlOk,
                'url_error' => $urlError,
                'url_finalizar' => $urlFinalizar,
                'signature' => $signature,
                'name_shopper' => $this->context->customer->firstname,
                'las_name_Shopper' => $this->context->customer->lastname,
                'email' => $this->context->customer->email,
                'country_code' => Country::getIsoById($addressdelivery->id_country),
                'phone' =>  $addressdelivery->phone,
                'mobile' =>  $addressdelivery->phone
            ];

            $urlPayment = $this->_buildRequest($data);

            if($this->handle_payment === 'redirection')
                Tools::redirectLink($urlPayment);
            $smarty->assign([
                'urlPayment' => $urlPayment
            ]);

        }catch (\Exception $exception){
            $this->logger()->logError($exception->getMessage());
            $smarty->assign([
                'msg' => $exception->getMessage()
            ]);
        }

        return $this->display(__FILE__, "payment_return.tpl");
    }

    /**
     * @param $params
     * @return array|void
     */
    public function hookPaymentOptions($params)
    {
        if (!$this->active ||
            empty($this->public_key) ||
            empty($this->secure_key) ||
            empty($this->end_point_url) ||
            !$this->checkCurrency($params['cart'])) return;

        $cart = $params['cart'];
        $idCurrency = $cart->id_currency;
        $currency = $this->getSingleCurrency($idCurrency);

        $paymentMethods = [];

        if(in_array($currency, self::ACCEPT_CURRENCIES_PAYMENT_ONLINE)){
            $online = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
            $online->setCallToActionText($this->l('Just Pay Online'))
                ->setAction($this->context->link->getModuleLink($this->name, 'validation', ['payMethod' => 1]))
                ->setAdditionalInformation($this->fetch('module:justpay/views/templates/front/online.tpl'))
                ->setLogo(Media::getMediaPath($this->_path."views/img/online-$currency.png"));
            $paymentMethods = array_merge($paymentMethods, [$online]);
        }

        if(in_array($currency, self::ACCEPT_CURRENCIES_PAYMENT_CASH)){
            $cash = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
            $cash->setCallToActionText($this->l('Just Pay Cash'))
                ->setAction($this->context->link->getModuleLink($this->name, 'validation', ['payMethod' => 2]))
                ->setAdditionalInformation($this->fetch('module:justpay/views/templates/front/cash.tpl'))
                ->setLogo(Media::getMediaPath($this->_path."views/img/cash-$currency.png"));
            $paymentMethods = array_merge($paymentMethods, [$cash]);
        }

        if(in_array($currency, self::ACCEPT_CURRENCIES_PAYMENT_CARDS)){
            $cards = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
            $cards->setCallToActionText($this->l('Just Pay Credit and debit cards'))
                ->setAction($this->context->link->getModuleLink($this->name, 'validation', ['payMethod' => 3]))
                ->setAdditionalInformation($this->fetch('module:justpay/views/templates/front/cards.tpl'))
                ->setLogo(Media::getMediaPath($this->_path."views/img/cards-$currency.png"));
            $paymentMethods = array_merge($paymentMethods, [$cards]);
        }

        return $paymentMethods;
    }

    public function checkCurrency($cart)
    {
        $currency_order = new Currency((int)($cart->id_currency));
        $currencies_module = $this->getCurrency((int)$cart->id_currency);
        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency'])
                    return true;
            }
        }
        return false;
    }

    public function logger()
    {
        require_once(dirname(__FILE__) . '/classes/JustPayLogger.php');
        return new JustPayLogger();
    }

    /**
     * @param $data
     * @throws Exception
     */
    protected function _buildRequest($data)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $this->end_point_url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $this->isSSL());
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        $result = curl_exec($curl);
        $api_http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($result === false)
            throw new  \Exception(curl_error($curl));
        if ($api_http_code !== 200)
            throw new  \Exception('Error', $api_http_code);
        curl_close($curl);

        return $result;
    }

    public function isSSL()
    {
        return strpos($this->end_point_url, 'https') !== false;
    }

    public function getSingleCurrency($idCurrency)
    {
        foreach ($this->getCurrency() as $mon) {
            if ($idCurrency == $mon['id_currency']) $currency = $mon['iso_code'];
        }

        return $currency;
    }

    public function updateStatusPay($idOrder, $status = 'APPROVED')
    {
        $state = 'PS_OS_PAYMENT';

        if ($status == 'ERROR')
            $state = 'JUSTPAY_OS_FAILED';

        $order = new Order((int)Order::getOrderByCartId((int)$idOrder));
        $current_state = $order->current_state;
        if ($current_state != Configuration::get('PS_OS_PAYMENT'))
        {
            $history = new OrderHistory();
            $history->id_order = (int)$order->id;
            $history->date_add = date("Y-m-d H:i:s");
            $history->changeIdOrderState((int)Configuration::get($state), (int)$order->id);
            $history->addWithemail(false);
        }
        if ($state != 'PS_OS_PAYMENT'){
            foreach ($order->getProductsDetail() as $product)
                StockAvailable::updateQuantity($product['product_id'], $product['product_attribute_id'], + (int)$product['product_quantity'], $order->id_shop);
        }
    }
}