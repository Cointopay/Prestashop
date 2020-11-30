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

$(document).ready(function () {

    var merchant_id = $("#COINTOPAY_MERCHANT_ID").val();
	var TransactionID = $("#COINTOPAY_TransactionID").val();
	var CustomerReferenceNr = $("#CustomerReferenceNr").val();
	setInterval(function() {

						$.ajax ({
							url: '/module/cointopay/cointopaywaiting',
							showLoader: true,
							type: "POST",
							data: {merchant: merchant_id, TransactionID: TransactionID, orderID: CustomerReferenceNr},
							success: function(result) {
							var cointopay_response = $.parseJSON(result);			
                            if (cointopay_response[1] == 'paid') {
								$("#CoinsPaymentStatus").val(cointopay_response[1]);
								$("#CoinsPaymentnotenough").val(0);
								$("#CoinsPaymentCallBack").submit();
							
							 }  else if (cointopay_response[1] == 'failed') {
								$("#CoinsPaymentStatus").val(cointopay_response[1]);
								$("#CoinsPaymentCallBack").submit();
							}
							else if (cointopay_response[1] == 'underpaid') {
								$("#CoinsPaymentStatus").val(cointopay_response[1]);
								$("#CoinsPaymentnotenough").val(1);
								$("#CoinsPaymentCallBack").submit();
							}
							else if (cointopay_response[1] == 'expired') {
								$("#CoinsPaymentStatus").val(cointopay_response[1]);
								$("#CoinsPaymentCallBack").submit();
							}
                                            
                                        }
                                    });
                                
                            }, 30000);
});