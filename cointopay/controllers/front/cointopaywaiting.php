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

class CointopayCointopaywaitingModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();

        try {
            if (isset($_REQUEST['merchant'])) {
				$mernt = $_REQUEST['merchant'];
				$TransID = $_REQUEST['TransactionID'];
				$orderID = $_REQUEST['orderID'];
				
				$url = 'https://cointopay.com/CloneMasterTransaction?MerchantID='.$mernt.'&TransactionID='.$TransID.'&output=json';
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, $url);
				$output=curl_exec($ch);
				curl_close($ch);
				$decoded = json_decode($output);
				$status_res = json_decode($output, true);
				if($status_res[1] == 'waiting'){
					$order = new Order($orderID);
					if($order->getCurrentOrderState()->name[1] == 'Waiting for cointopay transaction'){
						$history = new OrderHistory();
						$history->id_order = $orderID;
						$history->changeIdOrderState((int)Configuration::get('COINTOPAY_WAITING'), $orderID);
						$history->addWithemail(true, array(
							'order_name' => $orderID,
						));
					}
				}
				print_r($output);die;
			}
        } catch (Exception $e) {
            $this->context->smarty->assign(array(
                'text' => get_class($e) . ': ' . $e->getMessage()
            ));
			if (_PS_VERSION_ >= '1.7') {
				$this->setTemplate('module:cointopay/views/templates/front/ctp_payment_cancel.tpl');
			} else {
				$this->setTemplate('ctp_payment_cancel.tpl');
			}
        }
		
    }

    private function logError($message, $cart_id)
    {
        PrestaShopLogger::addLog($message, 3, null, 'Cart', $cart_id, true);
    }
}
