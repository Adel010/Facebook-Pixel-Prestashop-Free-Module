# Facebook Pixel Installer

A free open source PrestaShop module that allows you to easly install Facebook Pixel in PrestaShop.

#### Facebook events handled :

- ViewContent (for products view, it sends to facebook all the required informations about the product being viewed by the client).
- addToCart (it sends to facebook all the required informations about the product added to the cart by the client)
- InitiateCheckout (when the user hits the adress step of the checkout process)
- AddPaymentInfo (when the user hits the last step of the checkout process)
- Purchase (fired when the client arrives to order-confirmation page, and it sends to facebook the total amout of the order)
- Contact (fired in the contact page, no informations to send to facebook)
- Search (when a client search for somthing in your shop it sends to facebook the text being searched)

## Download

[Latest releases version (0.0.3)](https://github.com/Adel010/Facebook-Pixel-Prestashop-Free-Module/releases/tag/0.0.3)

## Installation

- Download the release ZIP file.
- In your PrestaShop admin, go to Modules>Modules Manager.
- In the Modules Manager page, click on "Install a module" button located on the top of the page.
- Select the ZIP file that you downloaded on step 1 then upload it, the installation will start automatically.
- Once the installion done, a configartion button will appear click on it then you'll be taken to the configuration page where can enter your Facebook Pixel ID.

## Configuration

- In your PrestaShop admin, go to Modules>Modules Manager.
- Look for "Facebook Pixel Installer" module and click the "Configure" button.
- In the configuration page, enter your Facebook Pixel ID then click the "Save" button.
