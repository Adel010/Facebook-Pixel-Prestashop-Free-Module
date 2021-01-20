{* 
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   https://opensource.org/licenses/GPL-3.0  GNU General Public License version 3
*}
{if $pixel_id}
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
        // product page view event
        fbq('track', 'ViewContent', {ldelim}
            content_name: prestashop.page.meta.title,
            content_category : '{$cat}'.replace(/&gt;/g, '>'),
            content_type: 'product',
            content_ids: [{$product_id}],
            value: {$product_price},
            currency: prestashop.currency.iso_code
        {rdelim});
    {/if}
    {if $addtocart}
        {literal}
        window.onload = function(){
            let initCount = prestashop.cart.products.length;
            console.log(initCount, "catched");
            prestashop.addListener("updateCart", function(){
                console.log("event fired");
                if(prestashop.cart.products.length > initCount){
                    console.log("condition passed");
                    let fbpatcp = prestashop.cart.products[initCount];
                    console.log(fbpatcp);
                    fbq('track', 'AddToCart', {
                        content_name: fbpatcp.name,
                        content_category: fbpatcp.category,
                        content_ids: [parseInt(fbpatcp.id)],
                        content_type: 'product',
                        value: fbpatcp.price_with_reduction,
                        currency: prestashop.currency.iso_code
                    });
                }
            });
        }
        {/literal}
    {/if}
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=<{$pixel_id}>&ev=PageView&noscript=1"/>
        </noscript> 
            <!-- Facebook Pixel Installer PrestaShop free module : https://github.com/Adel010/Facebook-Pixel-Prestashop-Free-Module -->
            <!-- End Facebook Pixel Code -->
{/if}
