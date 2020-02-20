<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

require_once(_PS_MODULE_DIR_ . '/cointopay/vendor/cointopay/init.php');
require_once(_PS_MODULE_DIR_ . '/cointopay/vendor/version.php');

class CointopayCallbackModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();
        
        $cart_id = Tools::getValue('CustomerReferenceNr');
        
        $order_id = Order::getOrderByCartId($cart_id);
        
        $order = new Order($order_id);

        try {
            if (!$order) {
                $error_message = 'Cointopay Order #' . Tools::getValue('CustomerReferenceNr') . ' does not exists';

                $this->logError($error_message, $cart_id);
                throw new Exception($error_message);
            }

            $ctp_order_status = Tools::getValue('status');

            if ($ctp_order_status == 'paid') {
                $order_status = 'PS_OS_PAYMENT';
            } elseif ($ctp_order_status == 'failed') {
                $order_status = 'COINTOPAY_FAILED';
                $this->logError('PS Orders is failed', $cart_id);
            } elseif ($ctp_order_status == 'canceled') {
                $order_status = 'PS_OS_CANCELED';
            } elseif ($ctp_order_status == 'refunded') {
                $order_status = 'PS_OS_REFUND';
            } else {
                $order_status = false;
            }

            if ($order_status !== false && $order_status == 'PS_OS_PAYMENT') {
                $history = new OrderHistory();
                $history->id_order = $order->id;
                $history->changeIdOrderState((int)Configuration::get($order_status), $order->id);
                $history->addWithemail(true, array(
                    'order_name' => Tools::getValue('CustomerReferenceNr'),
                ));
                $this->context->smarty->assign(array('text' => $cart_id));
                if (_PS_VERSION_ >= '1.7') {
                    $this->setTemplate('module:cointopay/views/templates/front/ctp_payment_success.tpl');
                } else {
                    $this->setTemplate('ctp_payment_success.tpl');
                }
            } elseif ($order_status == 'COINTOPAY_PNOTENOUGH' || $order_status == 'PS_OS_REFUND') {
                $history = new OrderHistory();
                $history->id_order = $order->id;
                $history->changeIdOrderState((int)Configuration::get($order_status), $order->id);
                $history->addWithemail(true, array(
                    'order_name' => Tools::getValue('CustomerReferenceNr'),
                ));

                Tools::redirect($this->context->link->getModuleLink('cointopay', 'cancel'));
            } else {
                $this->context->smarty->assign(array(
                    'text' => 'Order Status ' . $ctp_order_status . ' not implemented'
                ));
            }
        } catch (Exception $e) {
            $this->context->smarty->assign(array(
                'text' => get_class($e) . ': ' . $e->getMessage()
            ));
        }
        
        if (_PS_VERSION_ >= '1.7') {
            $this->setTemplate('module:cointopay/views/templates/front/ctp_payment_callback.tpl');
        } else {
            $this->setTemplate('cpt_payment_callback.tpl');
        }
    }

    private function logError($message, $cart_id)
    {
        PrestaShopLogger::addLog($message, 3, null, 'Cart', $cart_id, true);
    }
}
