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
    getCoin(merchant_id);

    $("#COINTOPAY_MERCHANT_ID").keyup(function () {
        var id = this.value;
        getCoin(id);
    });
});

function getCoin(id) {

    var selected_currency = $('#selected_currency').val();
    var currency_url = '../modules/cointopay/ajaxcall.php';
    if (id.length > 0) {
        $.ajax({
            url: currency_url,
            type: "POST",
            data: {merchant: id},
            success: function (result) {

                var data = $.parseJSON(result);
                var str = "";
                var $crypto_currency = $('#crypto_currency');

                $.each(data, function (index, value) {
                    if (data[index].id != 0) {
                        str += "<option value='" + data[index].id + "'> " + data[index].name + "</option>";
                    }
                });

                $crypto_currency.html(str);
                if (selected_currency != '' && selected_currency != 0) {
                    $crypto_currency.val(selected_currency);
                }
            },
            error: function () {
                console.log("error");
            }
        });
    }
}