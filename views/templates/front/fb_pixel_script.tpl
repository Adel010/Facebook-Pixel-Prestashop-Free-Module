{* 
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   https://opensource.org/licenses/AFL-3.0  Academic Free License ("AFL") v. 3.0
*}
{if $px_is_active and isset($pixel_id)}
    {literal}
        <!-- Facebook Pixel Code -->
    <script> 
        !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
    {/literal}
        fbq('init',{$pixel_id});
        fbq('track', 'PageView');
    {if $page_name eq 'product' and $view_product}
        fbq('track', 'ViewContent', {ldelim}
            content_name: prestashop.page.meta.title,
            content_category : "{$product_category}",
            content_type: 'product',
            content_ids: [{$product_id}],
            value: {$product_price},
            currency: prestashop.currency.iso_code
        {rdelim});
    {/if}
    {if $addtocart}
        {literal}
        window.addEventListener('load', function(){
            let init_products_count = prestashop.cart.products_count;
            prestashop.addListener("updateCart", function(e){
                if(e.reason.cart.products_count > init_products_count){
                    const prod = e.reason.cart.products.find(p => p.id == e.reason.idProduct);
                    fbq('track', 'AddToCart', {
                        content_name: prod.name,
                        content_category: prod.category,
                        content_ids: [e.reason.idProduct],
                        content_type: 'product',
                        contents: [{'id' : e.reason.idProduct, 'quantity': Number(prod.quantity_wanted), 'attributes' : prod.attributes}],
                        value: prod.price_wt,
                        currency: prestashop.currency.iso_code
                    });
                }
            });
        });
        {/literal}
    {/if}
    {if $page_name eq 'order-confirmation' and  $order_total}
        {strip}
        fbq('track', 'Purchase', {ldelim}
            content_type: 'product',
            content_ids: [{foreach from=$order_content_ids item=id name=ids}"{$id|escape:'javascript' nofilter}"{if !$smarty.foreach.ids.last},{/if}{/foreach}],
            contents: [{foreach from=$order_contents item=item name=items}{ldelim}id: {$item.id|intval},product_name: "{$item.product_name|escape:'javascript'}",product_attribute_id: {$item.product_attribute_id|intval},quantity: {$item.quantity|intval},item_price: {$item.item_price|floatval}{rdelim}{if !$smarty.foreach.items.last},{/if}{/foreach}],
            num_items: {count($order_content_ids)},
            currency: prestashop.currency.iso_code,
            value: {$order_total}
        {rdelim}
        );
        {/strip}
    {/if}
    {if $page_name eq 'checkout' and  $checkout and $checkout_step eq 'checkout-personal-information-step'} 
        fbq('track', 'InitiateCheckout', {ldelim}
            content_type: 'product',
            content_ids: prestashop.cart.products.map(p => p.id),
            contents: prestashop.cart.products.map(p => {ldelim}return {ldelim}'id': p.id, 'quantity' : p.quantity{rdelim}{rdelim}),
            num_items: prestashop.cart.products_count,
            currency: prestashop.currency.iso_code,
            value: prestashop.cart.totals.total.amount
            {rdelim}
        );
    {/if}
    {if $page_name eq 'checkout' and  $paymentinfo and $checkout_step eq 'checkout-payment-step'}
        fbq('track', 'AddPaymentInfo', {ldelim}
            content_type: 'product',
            content_ids: prestashop.cart.products.map(p => p.id),
            contents: prestashop.cart.products.map(p => {ldelim}return {ldelim}'id': p.id, 'quantity' : p.quantity{rdelim}{rdelim}),
            currency: prestashop.currency.iso_code,
            value: prestashop.cart.totals.total.amount
            {rdelim}
        );
    {/if}
    {if $page_name eq 'contact' and $contact}
        fbq('track', 'Contact');
    {/if}
    {if $page_name eq 'search' and $search}
        fbq('track', 'Search', {ldelim}search_string: "{$search_str}"{rdelim});
    {/if}
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={$pixel_id}&ev=PageView&noscript=1"/></noscript>
        <!-- Facebook Pixel Installer PrestaShop free module : https://github.com/Adel010/Facebook-Pixel-Prestashop-Free-Module -->
        <!-- End Facebook Pixel Code -->
{/if}