<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author PrestaShop SA <contact@prestashop.com>
 * @copyright  2007-2015 PrestaShop SA
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . '/cointopay/vendor/cointopay/init.php';
require_once _PS_MODULE_DIR_ . '/cointopay/vendor/version.php';

class CointopayValidationModuleFrontController extends ModuleFrontController
{
    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {
        $selected_currency = Tools::getValue('selected_currency');
        $cart = $this->context->cart;
        if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'cointopay') {
                $authorized = true;
                break;
            }
        }
        if (!$authorized) {
            exit($this->module->l('This payment method is not available.', 'validation'));
        }
        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer)) {
            Tools::redirect('index.php?controller=order&step=1');
        }
        $currency = $this->context->currency;
        $total = (float) $cart->getOrderTotal(true, Cart::BOTH);
        $this->module->validateOrder(
            $cart->id,
            Configuration::get('COINTOPAY_PENDING'),
            $total,
            $this->module->displayName,
            null,
            [],
            (int) $currency->id,
            false,
            $customer->secure_key
        );
        $link = new Link();
        $success_url = $link->getPageLink('order-confirmation', null, null, [
            'id_cart' => $cart->id,
            'id_module' => $this->module->id,
            'key' => $customer->secure_key,
        ]);
        $description = [];
        foreach ($cart->getProducts() as $product) {
            $description[] = $product['cart_quantity'] . ' × ' . $product['name'];
        }

        $merchant_id = Configuration::get('COINTOPAY_MERCHANT_ID');
        $security_code = Configuration::get('COINTOPAY_SECURITY_CODE');
        $user_currency = !empty($selected_currency) ? $selected_currency : Configuration::get('COINTOPAY_CRYPTO_CURRENCY');
        $selected_currency = !empty($user_currency) ? $user_currency : 1;
        $ctpConfig = [
            'merchant_id' => $merchant_id,
            'security_code' => $security_code,
            'selected_currency' => $selected_currency,
            'user_agent' => 'Cointopay - Prestashop v' . _PS_VERSION_ . ' Extension v' . COINTOPAY_PRESTASHOP_EXTENSION_VERSION,
        ];
        $orderObj = new Order($this->module->currentOrder);
        \cointopay\Cointopay::config($ctpConfig);
        $order = \cointopay\Merchant\Order::createOrFail([
            'order_id' => implode('----', [$orderObj->reference, $this->module->currentOrder]),
            'price' => $total,
            'currency' => $this->currencyCode($currency->iso_code),
            'cancel_url' => $this->flashEncode($this->context->link->getModuleLink('cointopay', 'cancel')),
            'callback_url' => $this->flashEncode($this->context->link->getModuleLink('cointopay', 'callback')),
            'success_url' => $success_url,
            'title' => Configuration::get('PS_SHOP_NAME') . ' Order #' . $cart->id,
            'description' => implode(', ', $description),
            'selected_currency' => $selected_currency,
        ]);
        // Ensure $confirmation_url is always defined
        $confirmation_url = '';
        if (isset($order)) {
            if (is_string($order)) {
                $validation_url = $this->context->link->getModuleLink('cointopay', 'cointopayvalidation', ['ctp_response' => $order], true);
                Tools::redirect($validation_url);
            } elseif (null != $order->Tag && $order->Tag != '') {
                $confirmation_url = $link->getPageLink('order-confirmation', null, null, [
                    'id_cart' => $cart->id,
                    'id_module' => $this->module->id,
                    'key' => $customer->secure_key,
                    'id_order' => $this->module->currentOrder,
                    'QRCodeURL' => $order->QRCodeURL,
                    'TransactionID' => $order->TransactionID,
                    'CoinName' => $order->CoinName,
                    'RedirectURL' => $order->shortURL,
                    'merchant_id' => $merchant_id,
                    'ExpiryTime' => $order->ExpiryTime,
                    'Amount' => $order->Amount,
                    'CustomerReferenceNr' => $order->CustomerReferenceNr,
                    'coinAddress' => $order->coinAddress,
                    'ConfirmCode' => $order->Security,
                    'AltCoinID' => $order->AltCoinID,
                    'SecurityCode' => $order->SecurityCode,
                    'inputCurrency' => $order->inputCurrency,
                    'CtpTag' => $order->Tag,
                    'ChainName' => $order->ChainName,
                ]);
            } else {
                $confirmation_url = $link->getPageLink('order-confirmation', null, null, [
                    'id_cart' => $cart->id,
                    'id_module' => $this->module->id,
                    'key' => $customer->secure_key,
                    'id_order' => $this->module->currentOrder,
                    'QRCodeURL' => $order->QRCodeURL,
                    'TransactionID' => $order->TransactionID,
                    'CoinName' => $order->CoinName,
                    'RedirectURL' => $order->shortURL,
                    'merchant_id' => $merchant_id,
                    'ExpiryTime' => $order->ExpiryTime,
                    'Amount' => $order->Amount,
                    'CustomerReferenceNr' => $order->CustomerReferenceNr,
                    'coinAddress' => $order->coinAddress,
                    'ConfirmCode' => $order->Security,
                    'AltCoinID' => $order->AltCoinID,
                    'SecurityCode' => $order->SecurityCode,
                    'inputCurrency' => $order->inputCurrency,
                    'ChainName' => $order->ChainName,
                ]);
            }
            Tools::redirect($confirmation_url);
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
    public function flashEncode($input)
    {
        return rawurlencode(utf8_encode($input));
    }
    /**
     * Currency code
     *
     * @param $isoCode
     * @return string
     */
    public function currencyCode($isoCode)
    {
        $currencyCode = '';

        if (isset($isoCode) && ($isoCode == 'RUB')) {
            $currencyCode = 'RUR';
        } else {
            $currencyCode = $isoCode;
        }

        return $currencyCode;
    }
}
