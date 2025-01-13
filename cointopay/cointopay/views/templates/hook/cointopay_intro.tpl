{**
 * 2007-2023 PrestaShop and Contributors
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
 * @copyright 2007-2025 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

<section>
 <input type="hidden" id="merchantId" value="{$merchant_id|escape:'htmlall':'UTF-8'}" />
 <input type="hidden" id="selectedCurrency" value="{$selected_currency|escape:'htmlall':'UTF-8'}" />
  <p>
    {l s='Cryptocurrency payments are processed by Cointopay - over 200 tokens supported.' d='cointopay' mod='cointopay'}
  </p>
  <select id="crypto_currency" class="form-control form-control-select" name="selected_currency">
    <option>Select default checkout currency</option>
  </select>
  <div class="modal fade" id="cointopay-modal" tabindex="-1" role="dialog" aria-labelledby="Cointopay information" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h2>Bitcoin, Ethereum, Litecoin or many other</h2>
        </div>
        <div class="modal-body">
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script type="text/javascript">
jQuery(document).ready(function ($) {
  $(document).ready(function () {

    var merchant_id = $("#merchantId").val();
    getCoin(merchant_id);
});

function getCoin(id) {

    var selected_currency = $('#selectedCurrency').val();
    var postdata = {
        ajax: 1,
        merchant: id
    };
    var url = '{$coins_ajax_link|escape:"htmlall":"UTF-8"}';
    if (url !== '') {
        url = url.replaceAll('&amp;', '&')
    }
    if (id.length > 0) {
        $.ajax({
            url: url,
            type: "POST",
            data: postdata,
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
                $(document).on('change', '#crypto_currency', function() {
                  $('input[name="selected_currency"]').val($('option:selected', this).val());
                });
            },
            error: function () {
                console.log("error");
            }
        });
    }
}
});
</script>