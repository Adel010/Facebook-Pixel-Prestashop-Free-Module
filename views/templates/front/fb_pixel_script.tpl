{* 
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   MIT
*}
{if $pixel_id != ""}
    {literal}
        <!-- Facebook Pixel Code --> 
        <script> 
            !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod? 
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n; 
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0; 
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, 
            document,'script','//connect.facebook.net/en_US/fbevents.js'); 
    {/literal}
            fbq('init',{$pixel_id});

    {literal}
            fbq('track', 'ViewContent');            
        </script>

        <noscript><img height="1" width="1" style="display:none" 
            src="https://www.facebook.com/tr?id=<{$pixel_id}>&ev=PageView&noscript=1" 
            />
        </noscript> 
            <!-- End Facebook Pixel Code --> 
    {/literal}    
{/if}
