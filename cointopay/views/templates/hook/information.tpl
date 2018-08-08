<div class="tab">
  <button class="tablinks" onclick="changeTab(event, 'Information')" id="defaultOpen">Information</button>
  <button class="tablinks" onclick="changeTab(event, 'Configure Settings')">Configure Settings</button>
</div>

<!-- Tab content -->
<div id="Information" class="tabcontent">
	<div class="wrapper">
	  <img src="../modules/cointopay/views/img/invoice.png" style="float:right;"/>
	  <h2 class="cointopay-information-header">Accept Bitcoin, Litecoin, Ethereum and other digital currencies on your PrestaShop store with Cointopay</h2><br/>
	  <strong> What is Cointopay? </strong> <br/>
	  <p>We offer a fully automated cryptocurrency processing platform and invoice system. Accept any cryptocurrency and get paid in Euros or U.S. Dollars directly to your bank account (for verified merchants), or just keep bitcoins!</p><br/>
	  <strong>Getting started</strong><br/>
	  <p>
	  	<ul>
	  		<li>Install the Cointopay module on PrestaShop</li>
	  		<li>Visit <a href="https://cointopay.com" target="_blank">cointopay.com</a> and create an account</li>
	  		<li>Get your API Key, Merchant ID and Security Code copy-paste them to the Configuration page in Cointopay module</li>
	  	</ul>
	  </p>
	  <img src="../modules/cointopay/views/img/currencies.png" style="float:right;"/>
	  <p class="sign-up"><br/>
	  	<a href="https://cointopay.com/Signup.jsp" class="sign-up-button">Sign up on Cointopay</a>
	  </p><br/>
	  <strong>Features</strong>
	  <p>
	  	<ul>
	  		<li>The gateway is <strong>fully automatic</strong> - set and forget it.</li>
	  		<li>Payment amount is calculated using <strong> real-time exchange rates</strong>.</li>
	  		<li>Your customers can select to <strong> pay with Bitcoin, Litecoin, Ethereum and 200+ other cryptocurrencies </strong> at checkout, while your payouts are in single currency of your choice.</li>
	  		<li><strong> No chargebacks</strong> - guaranteed!</li>
	  	</ul>
	  </p>

	  <p><i> Questions? Contact support@cointopay.com ! </i></p>
	</div>
</div>

<div id="Configure Settings" class="tabcontent">
  {html_entity_decode($form|escape:'htmlall':'UTF-8')}
  <input type="hidden" name="selected_currency" id="selected_currency" value="{html_entity_decode($selected_currency|escape:'htmlall':'UTF-8')}" >
</div>


<script type="text/javascript">
	document.getElementById("defaultOpen").click();
</script>