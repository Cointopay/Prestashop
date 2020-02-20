<section>
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
     
   <div class="col-md-6 col-sm-6 hidden-xs-down">
        <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Checkout# </td>
                                <td>{$smarty.get.CustomerReferenceNr}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Transaction ID </td>
                                <td>{$smarty.get.TransactionID}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Payment Address </td>
                                <td>{$smarty.get.coinAddress} </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Amount </td>
                                <td>{$smarty.get.Amount} bitcoin <img src="https://s3-eu-west-1.amazonaws.com/cointopay/img/bitcoin_dash2.png" style="width:20px;margin-top: -4px;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr style="height: 50px;">
                                <td style="width: 200px;">Expiry </td>
                                <td><span id="expire_time">{$smarty.get.ExpiryTime} </span></td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="form">
                        <tbody>
                            <tr>
                                <td style="width: 200px;">For more payment details</td>
                                <td><a href="{$smarty.get.RedirectURL}" style="" target="_blank">Click here</a></td>
                            </tr>
                        </tbody>
                    </table>  
            
        </div>
        <div class="col-md-6 col-sm-6">
          <div style="text-align: center;">
                    <img src="/modules/cointopay/views/img/cointopay.gif" style="margin: auto; display: table;margin-bottom: 20px;">
                       <img src="{$smarty.get.QRCodeURL}">
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
<form method="post" action="../modules/cointopay/controllers/front/callback" id="CoinsPaymentCallBack">
<input type="hidden" name="{$smarty.get.CustomerReferenceNr}" value="{$smarty.get.CustomerReferenceNr}" />
<input type="hidden" name="status" id="CoinsPaymentStatus" value="" />
</form>
<script type="text/javascript">
jQuery(document).ready(function ($) {
 jQuery('#cointopay-modal-6-0').modal('show');
jQuery('.inline_popup_cointopay').click(function(){
jQuery('#cointopay-modal-6-0').modal('show');
});
$('html, body').animate({
        scrollTop: $('.cointopay-login-content').offset().top
    }, 'slow')
});
       
jQuery(document).ready(function ($) {
	
var d1 = new Date (),
                            d2 = new Date ( d1 );
                            d2.setMinutes ( d1.getMinutes() + {$smarty.get.ExpiryTime} );
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

setInterval(function() {
                                    var CustomerReferenceNr = {$smarty.get.CustomerReferenceNr};
									var TransactionID = {$smarty.get.TransactionID};
									var merchant_id = {$smarty.get.merchant_id};
                                    $.ajax ({
                                        url: '/cointopay/getcoinspaymenturl?TransactionID='+TransactionID+'&merchant='+merchant_id,
                                        showLoader: true,
                                        type: "POST",
                                        success: function(result) {
											var cointopay_response = JSON.parse(result);
                            if (cointopay_response[1] == 'paid') {
								$("#CoinsPaymentStatus").val(cointopay_response[1]);
								$("#CoinsPaymentCallBack").submit();
							
							 }  else if (cointopay_response[1] == 'failed') {
								$("#CoinsPaymentStatus").val(cointopay_response[1]);
								$("#CoinsPaymentCallBack").submit();
							}
							else if (cointopay_response[1] == 'expired') {
								$("#CoinsPaymentStatus").val(cointopay_response[1]);
								$("#CoinsPaymentCallBack").submit();
							}
                                            
                                        }
                                    });
                                
                            }, 5000);
                        
});                           
    </script>

