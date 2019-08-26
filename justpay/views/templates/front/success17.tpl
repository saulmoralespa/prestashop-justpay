{extends file=$layout}
{block name='content'}
    <section id="content">
        <div class="row">
            <h3>{l s='Payment has been successfully received' mod='justpay'}</h3>
            <a href="{$link->getPageLink('history', true)|escape:'html':'UTF-8'}" title="{l s='My orders' mod='justpay'}">
                {l s='See my orders' mod='justpay'}
            </a>
        </div>
    </section>
{/block}