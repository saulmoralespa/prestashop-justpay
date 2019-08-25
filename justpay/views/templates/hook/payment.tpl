{if in_array($currency, $currenciesOnline)}
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <p class="payment_module">
                <a class="bankwire"
                   style="background: url({$imgOnline}) 10px 5px no-repeat #fbfbfb;padding-left: 222px;"
                   href="{$link->getModuleLink('justpay', 'payment', ['payMethod' => 1])|escape:'html'}"
                   title="{l s='Just Pay Online' mod='justpay'}">
                    {l s='Just Pay Online' mod='justpay'}&nbsp;
                </a>
            </p>
        </div>
    </div>
{/if}
{if in_array($currency, $currenciesCash)}
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <p class="payment_module">
                <a class="bankwire"
                   style="background: url({$imgCash}) 10px 5px no-repeat #fbfbfb;padding-left: 222px;"
                   href="{$link->getModuleLink('justpay', 'payment', ['payMethod' => 2])|escape:'html'}"
                   title="{l s='Just Pay Cash' mod='justpay'}">
                    {l s='Just Pay Cash' mod='justpay'}&nbsp;
                </a>
            </p>
        </div>
    </div>
{/if}
{if in_array($currency, $currenciesCards)}
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <p class="payment_module">
                <a class="bankwire"
                   style="background: url({$imgCards}) 10px 5px no-repeat #fbfbfb;padding-left: 222px;"
                   href="{$link->getModuleLink('justpay', 'payment', ['payMethod' => 3])|escape:'html'}"
                   title="{l s='Just Pay Credit and debit cards' mod='justpay'}">
                    {l s='Just Pay Credit and debit cards' mod='justpay'}
                </a>
            </p>
        </div>
    </div>
{/if}