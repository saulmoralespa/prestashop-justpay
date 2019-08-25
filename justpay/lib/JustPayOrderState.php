<?php


class JustPayOrderState extends ObjectModel
{
    public static function getOrderStates($ids_only = false)
    {
        global $cookie;

        $returnStates = array();

        $states = OrderState::getOrderStates($cookie->id_lang);

        foreach($states as $k => $state)
        {
            if($ids_only)
            {
                $returnStates[] = $state['id_order_state'];
            }
            else
            {
                $returnStates[] = $state;
            }
        }
        return $returnStates;
    }



    public static function getInitialState()
    {
        return Configuration::get('JUSTPAY_ORDERSTATE_WAITING');
    }


    public static function updateStates($id_initial_state, $delete_on)
    {
        return true;
    }

    public static function setup()
    {

        if (!Configuration::get('JUSTPAY_ORDERSTATE_WAITING'))
        {
            $order_state = new OrderState();
            $order_state->name = array();
            foreach (Language::getLanguages() as $language)
                $order_state->name[$language['id_lang']] = 'Just Pay Esperando Pago';
            $order_state->send_email = false;
            $order_state->color = '#FEFF64';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;
            $order_state->add();
            Configuration::updateValue('JUSTPAY_ORDERSTATE_WAITING', (int)$order_state->id);
        }
        if (!Configuration::get('JUSTPAY_OS_PENDING'))
        {
            $order_state = new OrderState();
            $order_state->name = array();
            foreach (Language::getLanguages() as $language)
                $order_state->name[$language['id_lang']] = 'Just Pay Pago Pendiente';
            $order_state->send_email = false;
            $order_state->color = '#FEFF64';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;
            $order_state->add();
            Configuration::updateValue('JUSTPAY_OS_PENDING', (int)$order_state->id);
        }
        if (!Configuration::get('JUSTPAY_OS_FAILED'))
        {
            $order_state = new OrderState();
            foreach (Language::getLanguages() as $language)
                $order_state->name[$language['id_lang']] = 'Just Pay Pago Fallido';
            $order_state->send_email = false;
            $order_state->color = '#8F0621';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;
            $order_state->add();

            Configuration::updateValue('JUSTPAY_OS_FAILED', (int)$order_state->id);
        }
        if (!Configuration::get('JUSTPAY_OS_REJECTED'))
        {
            $order_state = new OrderState();
            foreach (Language::getLanguages() as $language)
                $order_state->name[$language['id_lang']] = 'Just Pay Pago Rechazado';
            $order_state->send_email = false;
            $order_state->color = '#8F0621';
            $order_state->hidden = false;
            $order_state->delivery = false;
            $order_state->logable = false;
            $order_state->invoice = false;
            $order_state->add();
            Configuration::updateValue('JUSTPAY_OS_REJECTED', (int)$order_state->id);
        }
    }

    public static function remove()
    {
        Configuration::deleteByName('JUSTPAY_ORDERSTATE_WAITING');
        Configuration::deleteByName('JUSTPAY_OS_PENDING');
        Configuration::deleteByName('JUSTPAY_OS_FAILED');
        Configuration::deleteByName('JUSTPAY_OS_REJECTED');

    }
}