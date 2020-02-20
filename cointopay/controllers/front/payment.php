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

class CointopayPaymentModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;

    public function initContent()
    {
        parent::initContent();

        $cart = $this->context->cart;

        if (!$this->module->checkCurrency($cart)) {
            Tools::redirect('index.php?controller=order');
        }

        $this->context->smarty->assign(array(
            'nbProducts'    => $cart->nbProducts(),
            'cust_currency' => $cart->id_currency,
            'currencies'    => $this->module->getCurrency((int)$cart->id_currency),
            'total'         => $cart->getOrderTotal(true, Cart::BOTH),
            'this_path'     => $this->module->getPathUri(),
            'this_path_bw'  => $this->module->getPathUri(),
            'this_path_ssl' => Tools::getShopDomainSsl(true, true)
                . __PS_BASE_URI__ . 'modules/' . $this->module->name . '/'
        ));

        $this->setTemplate('payment_execution.tpl');
    }
}
