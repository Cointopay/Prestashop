<?php
/**
 * 2007-2022 PrestaShop and Contributors
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
 * @copyright 2007-2022 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

class CointopayGetcoinsModuleFrontController extends ModuleFrontController
{
    public $auth = false;

    /** @var bool */
    public $ajax;

    public function displayAjax()
    {		
		$this->ajax = 1;

        try {
           if (Tools::getIsset('merchant')) {
				$merchant = Tools::getValue('merchant');

				$url = 'https://cointopay.com/CloneMasterTransaction?MerchantID=' . $merchant . '&output=json&JsonArray=1';
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $url,
				));
				$response = curl_exec($curl);

				$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
				curl_close($curl);

				if ($http_status === 200) {
					$this->ajaxRender($response);
				} else {
					$this->ajaxRender("no coins");
				}
			} else {
					$this->ajaxRender("no coins");
				}
        } catch (Exception $e) {
            echo $e->getMessage();
        }
		
    }
	
}