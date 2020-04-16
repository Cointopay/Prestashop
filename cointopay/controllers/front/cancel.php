	`<?php
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

class CointopayCancelModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();
        
        //$cart_id = Tools::getValue('CustomerReferenceNr');
		$cart = $this->context->cart;
        
        $order_id = Tools::getValue('CustomerReferenceNr');
		
		$TransactionID = Tools::getValue('TransactionID');
		
		$ConfirmCode = Tools::getValue('ConfirmCode');
		        
        $order = new Order($order_id);

        try {
            if (!$order) {
                $error_message = 'Cointopay Order #' . Tools::getValue('CustomerReferenceNr') . ' does not exists';

                $this->logError($error_message, $order_id);
                throw new Exception($error_message);
            }

			$ctp_order_status = Tools::getValue('status');
			$merchant_id = Configuration::get('COINTOPAY_MERCHANT_ID');
			$security_code = Configuration::get('COINTOPAY_SECURITY_CODE');
			$user_currency = Configuration::get('COINTOPAY_CRYPTO_CURRENCY');
			$selected_currency = (isset($user_currency) && !empty($user_currency)) ? $user_currency : 1;
			$ctpConfig = array(
			  'merchant_id' => $merchant_id,
			  'security_code'=>$security_code,
			  'selected_currency'=>$selected_currency,
			  'user_agent' => 'Cointopay - Prestashop v'._PS_VERSION_.' Extension v'.COINTOPAY_PRESTASHOP_EXTENSION_VERSION
			);

			\Cointopay\Cointopay::config($ctpConfig);
			$response_ctp = \Cointopay\Merchant\Order::ValidateOrder(array(
				'TransactionID'         => $TransactionID,
				'ConfirmCode'            => $ConfirmCode
			));
         
            if (isset($response_ctp)) {
				if($response_ctp->data['Security'] != $ConfirmCode)
				{
				   $this->context->smarty->assign(array('text' => $response_ctp->data->Security.'Data mismatch! ConfirmCode doesn\'t match'));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				elseif($response_ctp->data['CustomerReferenceNr'] != $order_id)
				{
				   $this->context->smarty->assign(array('text' => 'Data mismatch! CustomerReferenceNr doesn\'t match'));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				elseif($response_ctp->data['TransactionID'] != $TransactionID)
				{
				   $this->context->smarty->assign(array('text' => 'Data mismatch! TransactionID doesn\'t match'));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				elseif(null != Tools::getValue('AltCoinID') && $response_ctp->data['AltCoinID'] != Tools::getValue('AltCoinID'))
				{
				   $this->context->smarty->assign(array('text' => 'Data mismatch! AltCoinID doesn\'t match'));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				elseif(null != Tools::getValue('MerchantID') && $response_ctp->data['MerchantID'] != Tools::getValue('MerchantID'))
				{
				   $this->context->smarty->assign(array('text' => 'Data mismatch! MerchantID doesn\'t match'));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				elseif(null != Tools::getValue('CoinAddressUsed') && $response_ctp->data['coinAddress'] != Tools::getValue('CoinAddressUsed'))
				{
				   $this->context->smarty->assign(array('text' => 'Data mismatch! coinAddress doesn\'t match'));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				elseif(null != Tools::getValue('SecurityCode') && $response_ctp->data['SecurityCode'] != Tools::getValue('SecurityCode'))
				{
				   $this->context->smarty->assign(array('text' => 'Data mismatch! SecurityCode doesn\'t match'));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				elseif(null != Tools::getValue('inputCurrency') && $response_ctp->data['inputCurrency'] != Tools::getValue('inputCurrency'))
				{
				   $this->context->smarty->assign(array('text' => 'Data mismatch! inputCurrency doesn\'t match'));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				elseif($response_ctp->data['Status'] != $ctp_order_status)
				{
				   $this->context->smarty->assign(array('text' => 'We have detected different order status. Your order status is '.$response_ctp->data['Status']));
					if (_PS_VERSION_ >= '1.7') {
						$this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
					} else {
						$this->setTemplate('cointopay_payment_cancel.tpl');
					}
				}
				else{
					if ($ctp_order_status == 'paid') {
						$order_status = 'PS_OS_PAYMENT';
					} elseif ($ctp_order_status == 'failed') {
						$order_status = 'COINTOPAY_FAILED';
						$this->logError('PS Orders is failed', $order_id);
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
						$this->context->smarty->assign(array('text' => $order_id));
						if (_PS_VERSION_ >= '1.7') {
							$this->setTemplate('module:cointopay/views/templates/front/ctp_payment_cancel.tpl');
						} else {
							$this->setTemplate('ctp_payment_cancel.tpl');
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
					
				}
			}
			else {
				Tools::redirect('index.php?controller=order&step=3');
			}
        } catch (Exception $e) {
            $this->context->smarty->assign(array(
                'text' => get_class($e) . ': ' . $e->getMessage()
            ));
			if (_PS_VERSION_ >= '1.7') {
            $this->setTemplate('module:cointopay/views/templates/front/cointopay_payment_cancel.tpl');
        } else {
            $this->setTemplate('cointopay_payment_cancel.tpl');
        }
        }
        
        
		
    }

    private function logError($message, $cart_id)
    {
        PrestaShopLogger::addLog($message, 3, null, 'Cart', $cart_id, true);
    }
}
