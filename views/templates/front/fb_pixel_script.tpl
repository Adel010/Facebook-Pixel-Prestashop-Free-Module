{* 
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   MIT
*}
{literal}
    <!-- Facebook Pixel Code --> 
    <script> 
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod? 
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n; 
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0; 
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, 
        document,'script','//connect.facebook.net/en_US/fbevents.js'); 
        fbq('init', 'fb_pixel_id');
        fbq('track', 'ViewContent');
        {% if search.performed %}
        fbq('track', 'Search');
        {% elsif page.handle contains 'contact_posted=true' %}
        fbq('track', 'Lead');
        {% elsif template contains 'cart' %}
        fbq('track', 'AddToCart');
        {% elsif template contains 'product' %}
        fbq('track', 'ViewContent', {
        content_ids: ['{{ product.id }}_{{ product.variants.first.id }}'],
        content_type: 'product'
        });
        {% endif %}
        
    </script>

    <noscript><img height="1" width="1" style="display:none" 
        src="https://www.facebook.com/tr?id=<fb_pixel_id>&ev=PageView&noscript=1" 
        />
    </noscript> 
        <!-- End Facebook Pixel Code --> 
{/literal}