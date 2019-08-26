{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{capture name=path}{l s='Just Pay' mod='justpay'}{/capture}
<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="wrap">
        <div id="validation-justpay">
            <h1 class="page-heading">{l s='ORDER SUMMARY' mod='justpay'}</h1>
            {assign var='current_step' value='payment'}
            {include file="$tpl_dir./order-steps.tpl"}
            {if $nbProducts <= 0}
                <p class="warning" style="text-align: center; font-size: 16px;">{l s='Your shopping cart is empty.' mod='justpay'}</p>
            {else}
                <form action="{$link->getModuleLink('justpay', 'validation', ['payMethod' => $payMethod])|escape:'html'}" id="form-justpay" method="post">
                    <div class="box cheque-box">
                        <div>
                            <table style="width: 100%;">
                                <tr>
                                    <td style="border: solid 1px; text-align: center;">
                                        {l s='The total amount of your order is' mod='justpay'}
                                    </td>
                                    <td style="border: solid 1px;text-align: center;">
                                        <span id="amount" class="price">{displayPrice price=$total}</span>
                                        {if $use_taxes == 1}
                                            {l s='(VAT included)' mod='justpay'}
                                        {/if}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div id="cart_navigation" class="cart_navigation clearfix">
                        <input type="submit"
                               style="background:#F0943E;color:#FFFFFF;font-size:16px;border-radius:10px;"
                               value="{l s='CONFIRMED MY ORDER' mod='justpay'}"
                               class="button btn btn-default pull-right"/>
                    </div>
                </form>
            {/if}
        </div>
    </div>
</div>