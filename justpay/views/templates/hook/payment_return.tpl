{if isset($urlPayment)}
    <a
            class="iframe-lightbox-link" id="iframe-just-pay"
            href="{$urlPayment}">{l s='Pay with Just Pay' mod='justpay'}
    </a>
{else}
    <div class="cprow">
        <div class="cpcolumn">
            <div class="cpalert">
                {$msg}
            </div>
        </div>
    </div>
{/if}
