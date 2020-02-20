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

{extends "$layout"}

{block name="content"}
  <section>
    <p>{l s='You have successfully submitted your payment form.' mod='cointopay'}</p>
    <p>{l s='Here are the params:' mod='cointopay'}</p>
    <ul>
      {foreach from=$params key=name item=value}
        <li>{$this_path|escape:'htmlall':'UTF-8'}: {$value|escape:'htmlall':'UTF-8'}</li>
      {/foreach}
    </ul>
    <p>{l s='Now, you just need to proceed the payment and do what you need to do.' mod='cointopay'}</p>
  </section>
{/block}