{capture name=path}
    <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='cointopay'}">
        {l s='Checkout' mod='cointopay'}
    </a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>
    {l s='Cointopay payment' mod='cointopay'}
{/capture}

<h1 class="page-heading">
    {l s='Order summary' mod='cointopay'}
</h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
    <p class="alert alert-warning">
        {l s='Your shopping cart is empty.' mod='cointopay'}
    </p>
{else}
    <form action="{$link->getModuleLink('cointopay', 'redirect', [], true)|escape:'html':'UTF-8'}" method="post">
        <div class="box cheque-box">
            <h3 class="page-subheading">
                {l s='Cointopay payment' mod='cointopay'}
            </h3>

            <p class="cheque-indent">
                <strong class="dark">
                    {l s='You have chosen to pay with Cryptocurrency via Cointopay.' mod='cointopay'} {l s='Here is a short summary of your order:' mod='cointopay'}
                </strong>
            </p>

            <p>
                - {l s='The total amount of your order is' mod='cointopay'}
                <span id="amount" class="price">{displayPrice price=$total}</span>
                {if $use_taxes == 1}
                    {l s='(tax incl.)' mod='cointopay'}
                {/if}
            </p>

            <p>
                - {l s='You will be redirected to Cointopay for payment with Cryptocurrency.' mod='cointopay'}
                <br/>
                - {l s='Please confirm your order by clicking "I confirm my order".' mod='cointopay'}
            </p>
        </div>
        <p class="cart_navigation clearfix" id="cart_navigation">
            <a class="button-exclusive btn btn-default" href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}">
                <i class="icon-chevron-left"></i>{l s='Other payment methods' mod='cointopay'}
            </a>
            <button class="button btn btn-default button-medium" type="submit">
                <span>{l s='I confirm my order' mod='cointopay'}<i class="icon-chevron-right right"></i></span>
            </button>
        </p>
    </form>
{/if}