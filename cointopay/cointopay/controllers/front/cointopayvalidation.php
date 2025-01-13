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
if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . '/cointopay/vendor/cointopay/init.php';
require_once _PS_MODULE_DIR_ . '/cointopay/vendor/version.php';
class CointopayCointopayvalidationModuleFrontController extends ModuleFrontController
{
    public $ssl = true;

    public function initContent()
    {
        parent::initContent();
        $ctp_response = '';
        $ctp_response = Tools::getValue('ctp_response');

        try {
            if ($ctp_response != '') {
                $this->context->smarty->assign(['text' => 'BadCredentials:' . $ctp_response]);
                if (_PS_VERSION_ >= '1.7') {
                    $this->setTemplate('module:cointopay/views/templates/front/ctp_validation_failed.tpl');
                } else {
                    $this->setTemplate('ctp_validation_failed.tpl');
                }
            } else {
                Tools::redirect('index.php?controller=order&step=3');
            }
        } catch (Exception $e) {
            $this->context->smarty->assign(['text' => get_class($e) . ': ' . $e->getMessage()]);
            if (_PS_VERSION_ >= '1.7') {
                $this->setTemplate('module:cointopay/views/templates/front/ctp_validation_failed.tpl');
            } else {
                $this->setTemplate('ctp_validation_failed.tpl');
            }
        }
    }

    private function logError($message, $cart_id)
    {
        PrestaShopLogger::addLog($message, 3, null, 'Cart', $cart_id, true);
    }
}
