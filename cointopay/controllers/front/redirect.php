<?php

require_once(_PS_MODULE_DIR_ . '/cointopay/vendor/cointopay/init.php');
require_once(_PS_MODULE_DIR_ . '/cointopay/vendor/version.php');

class CointopayRedirectModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();

        $cart = $this->context->cart;

        if (!$this->module->checkCurrency($cart)) {
            Tools::redirect('index.php?controller=order');
        }

        $this->context->link->getModuleLink('cointopay', 'callback');
        $total = (float)number_format($cart->getOrderTotal(true, 3), 2, '.', '');
        $currency = Context::getContext()->currency;

        $description = array();
        foreach ($cart->getProducts() as $product) {
            $description[] = $product['cart_quantity'] . ' × ' . $product['name'];
        }

        $customer = new Customer($cart->id_customer);

        $link = new Link();
        $success_url = $link->getPageLink('order-confirmation', null, null, array(
          'id_cart'     => $cart->id,
          'id_module'   => $this->module->id,
          'key'         => $customer->secure_key
        ));

        $merchant_id = Configuration::get('COINTOPAY_MERCHANT_ID');
        $security_code = Configuration::get('COINTOPAY_SECURITY_CODE');
        $user_currency = Configuration::get('COINTOPAY_CRYPTO_CURRENCY');
        $selected_currency = (isset($user_currency ) && !empty($user_currency )) ? $user_currency : 1;

        $ctpConfig = array(
          'merchant_id' => $merchant_id,
          'security_code'=>$security_code,
          'selected_currency'=>$selected_currency,
          'user_agent' => 'Cointopay - Prestashop v'._PS_VERSION_.' Extension v'.COINTOPAY_PRESTASHOP_EXTENSION_VERSION
        );

        \Cointopay\Cointopay::config($ctpConfig);

        $order = \Cointopay\Merchant\Order::createOrFail(array(
            'order_id'         => $cart->id,
            'price'            => $total,
            'currency'         => $this->currencyCode($currency->iso_code),
            'cancel_url'       => $this->flash_encode($this->context->link->getModuleLink('cointopay', 'cancel')),
            'callback_url'     => $this->flash_encode($this->context->link->getModuleLink('cointopay', 'callback')),
            'success_url'      => $success_url,
            'title'            => Configuration::get('PS_SHOP_NAME') . ' Order #' . $cart->id,
            'description'      => join($description, ', '),
            'selected_currency'=> $selected_currency
        ));

        if (isset($order) ) {
            if (!$order->shortURL  ) {
                Tools::redirect('index.php?controller=order&step=3');
            }

            $this->module->validateOrder(
                $cart->id,
                Configuration::get('COINTOPAY_PROCESSING_IN_PROGRESS'),
                $total,
                $this->module->displayName,
                null,
                null,
                (int)$currency->id,
                false,
                $customer->secure_key
            );

            Tools::redirect($order->shortURL);
        } else {
            Tools::redirect('index.php?controller=order&step=3');
        }
    }

    /**
     * URL encode to UTF-8
     *
     * @param $input
     * @return string
     */
    public function flash_encode ($input)
    {
        return rawurlencode(utf8_encode($input));
    }

    /**
     * Currency code
     * @param $isoCode
     * @return string
     */
    public function currencyCode($isoCode){

        $currencyCode='';

        if( isset($isoCode) && ($isoCode == 'RUB')){
            $currencyCode='RUR';
        }else{
            $currencyCode= $isoCode;
        }
        return $currencyCode;
    }
}