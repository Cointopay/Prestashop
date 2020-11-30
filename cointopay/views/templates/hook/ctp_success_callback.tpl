{**
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
 *}

<section id="cointopay_order_confirmation_section">
    <h3 class="h3 card-title">Cointopay Payment details:</h3>
    <div class="cointopay-login-content">
 <p>To pay with Cointopay <a class="inline_popup_cointopay" href="#" rel="nofollow">Click here</a></p>
           
               <div id="cointopay-modal-6-0" class="modal fade cointopay_popup in" tabindex="-1" role="dialog" style="display: block;">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1150px;">
   <div class="modal-content">
     <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">×</span>
       </button>
     </div>
     <div class="modal-body">
 <h3 class="h3 card-title">Cointopay Payment details:</h3>

      <div class="row">
     
   <div class="col-md-8 col-sm-8 hidden-xs-down">
        <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Checkout# </td>
                                <td>{$smarty.get.CustomerReferenceNr|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Transaction ID </td>
                                <td>{$smarty.get.TransactionID|escape:'htmlall':'UTF-8'}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Payment Address </td>
                                <td>{$smarty.get.coinAddress|escape:'htmlall':'UTF-8'} </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Amount </td>
                                <td>{$smarty.get.Amount|escape:'htmlall':'UTF-8'} {$smarty.get.CoinName|escape:'htmlall':'UTF-8'} <img src="https://s3-eu-west-1.amazonaws.com/cointopay/img/{$smarty.get.CoinName}_dash2.png" style="width:20px;margin-top: -4px;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Expiry </td>
                                <td><span id="expire_time">{$smarty.get.ExpiryTime|escape:'htmlall':'UTF-8'} </span></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr>
                                <td style="width: 200px;">For more payment details</td>
                                <td><a href="{$smarty.get.RedirectURL|escape:'htmlall':'UTF-8'}" style="" target="_blank">Click here</a></td>
                            </tr>
                        </tbody>
                    </table>  
            
        </div>
        <div class="col-md-4 col-sm-4">
          <div style="text-align: center;">
                    <img src="/modules/cointopay/views/img/cointopay.gif" style="margin: auto; display: table;margin-bottom: 20px;">
                       <img width="100%" src="{html_entity_decode($smarty.get.QRCodeURL|escape:'htmlall':'UTF-8')}">
                    </div>
        
        </div>
      </div>
     </div>
     <div class="modal-footer"></div>
  

    </div>
   </div>
 </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<form method="post" action="/module/cointopay/callback" id="CoinsPaymentCallBack">
<input type="hidden" name="CustomerReferenceNr" id="CustomerReferenceNr" value="{$smarty.get.CustomerReferenceNr|escape:'htmlall':'UTF-8'}" />
<input type="hidden" name="ConfirmCode" id="ConfirmCode" value="{$smarty.get.ConfirmCode|escape:'htmlall':'UTF-8'}" />
<input type="hidden" name="status" id="CoinsPaymentStatus" value="" />
<input type="hidden" name="notenough" id="CoinsPaymentnotenough" value="" />
<input type="hidden" name="COINTOPAY_MERCHANT_ID" id="COINTOPAY_MERCHANT_ID" value="{$smarty.get.merchant_id|escape:'htmlall':'UTF-8'}" />
<input type="hidden" name="TransactionID" id="COINTOPAY_TransactionID" value="{$smarty.get.TransactionID|escape:'htmlall':'UTF-8'}" />
<input type="hidden" name="CoinAddressUsed" id="CoinAddressUsed" value="{$smarty.get.coinAddress|escape:'htmlall':'UTF-8'}" />
<input type="hidden" name="SecurityCode" id="SecurityCode" value="{$smarty.get.SecurityCode|escape:'htmlall':'UTF-8'}" />
<input type="hidden" name="AltCoinID" id="AltCoinID" value="{$smarty.get.AltCoinID|escape:'htmlall':'UTF-8'}" />
<input type="hidden" name="RedirectURL" id="RedirectURL" value="{$smarty.get.RedirectURL|escape:'htmlall':'UTF-8'}" />
</form>
<script type="text/javascript">
jQuery(document).ready(function ($) {
 jQuery('#cointopay-modal-6-0').modal('show');
jQuery('.inline_popup_cointopay').click(function(){
jQuery('#cointopay-modal-6-0').modal('show');
});
$('html, body').animate({
        scrollTop: $('#cointopay_order_confirmation_section').offset().top
    }, 'slow')
});
       
jQuery(document).ready(function ($) {
	
var d1 = new Date (),
                            d2 = new Date ( d1 );
                            d2.setMinutes ( d1.getMinutes() + {$smarty.get.ExpiryTime|escape:'htmlall':'UTF-8'} );
                            var countDownDate = d2.getTime();
                            // Update the count down every 1 second
                            var x = setInterval(function() {
                                if ($('#expire_time').length) {
                                    // Get todays date and time
                                    var now = new Date().getTime();
                                    
                                    // Find the distance between now an the count down date
                                    var distance = countDownDate - now;
                                    
                                    // Time calculations for days, hours, minutes and seconds
                                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    
                                    // Output the result in an element with id="expire_time"
                                    document.getElementById("expire_time").innerHTML = days + "d " + hours + "h "
                                    + minutes + "m " + seconds + "s ";
                                    
                                    // If the count down is over, write some text 
                                    if (distance < 0) {
                                        clearInterval(x);
                                        document.getElementById("expire_time").innerHTML = "EXPIRED";
                                    }
                                }
                            }, 1000);


                        
});                           
    </script>

