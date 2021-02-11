<?php
/**
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   https://opensource.org/licenses/AFL-3.0  Academic Free License ("AFL") v. 3.0
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
class Facebookpixelinstaller extends Module
{

    public function __construct()
    {
        $this->name = 'facebookpixelinstaller';
        $this->tab = 'advertising_marketing';
        $this->version = '0.0.3';
        $this->author = 'Adel Alikeche';
        $this->need_instance = 1;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Facebook Pixel Installer');
        $this->description = $this->l('Add the facebook pixel to your shop for free.');

        $this->confirmUninstall = $this->l('Are you sure that you want to remove the Facebook pixel from your shop ?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('FACEBOOKPIXELINSTALLER_LIVE_MODE', false);

        return parent::install() &&
            Configuration::updateValue('facebook_pixel_id', '') &&
            Configuration::updateValue('facebook_pixel_active', true) &&
            Configuration::updateValue('facebook_event_contentview_active', true) &&
            Configuration::updateValue('facebook_event_addtocart_active', true) &&
            Configuration::updateValue('facebook_event_purchase_active', true) &&
            Configuration::updateValue('facebook_event_contact_active', true) &&
            Configuration::updateValue('facebook_event_search_active', true) &&
            Configuration::updateValue('facebook_event_initcheckout_active', true) &&
            Configuration::updateValue('facebook_event_addpaymentinfo_active', true) &&
            $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('FACEBOOKPIXELINSTALLER_LIVE_MODE');

        return parent::uninstall() &&
        Configuration::deleteByName('facebook_pixel_id') &&
        Configuration::deleteByName('facebook_event_contentview_active') &&
        Configuration::deleteByName('facebook_event_addtocart_active') &&
        Configuration::deleteByName('facebook_event_purchase_active') &&
        Configuration::deleteByName('facebook_event_contact_active') &&
        Configuration::deleteByName('facebook_event_initcheckout_active') &&
        Configuration::deleteByName('facebook_event_addpaymentinfo_active') &&
        Configuration::deleteByName('facebook_event_search_active') &&
        Configuration::deleteByName('facebook_pixel_active');
    }


    public function getContent()
    {
        $_html = '';
        if (((bool)Tools::isSubmit('submit'.$this->name)) == true) {
            
            if(Tools::getValue('pixel_id') === '') {
                $_html .= '<div class="alert alert-danger" role="alert"><p class="alert-text">Please eneter your Facebook Pixel ID then save.</p></div>';
            } else {
                Configuration::updateValue('facebook_pixel_id', Tools::getValue('pixel_id'));
            }
            
            if(Tools::getValue('is_active') == 0) {
                Configuration::updateValue('facebook_pixel_active', false);
            } else {
                Configuration::updateValue('facebook_pixel_active', true);
            }
            if(Tools::getValue('product_active') == 0) {
                Configuration::updateValue('facebook_event_contentview_active', false);
            } else {
                Configuration::updateValue('facebook_event_contentview_active', true);
            }
            if(Tools::getValue('addtocart_active') == 0) {
                Configuration::updateValue('facebook_event_addtocart_active', false);
            } else {
                Configuration::updateValue('facebook_event_addtocart_active', true);
            }
            if(Tools::getValue('purchase_active') == 0) {
                Configuration::updateValue('facebook_event_purchase_active', false);
            } else {
                Configuration::updateValue('facebook_event_purchase_active', true);
            }
            if(Tools::getValue('contact_active') == 0) {
                Configuration::updateValue('facebook_event_contact_active', false);
            } else {
                Configuration::updateValue('facebook_event_contact_active', true);
            }
            if(Tools::getValue('search_active') == 0) {
                Configuration::updateValue('facebook_event_search_active', false);
            } else {
                Configuration::updateValue('facebook_event_search_active', true);
            }
            if(Tools::getValue('initiatecheckout_active') == 0) {
                Configuration::updateValue('facebook_event_initcheckout_active', false);
            } else {
                Configuration::updateValue('facebook_event_initcheckout_active', true);
            }
            if(Tools::getValue('addpaymentinfo_active') == 0) {
                Configuration::updateValue('facebook_event_addpaymentinfo_active', false);
            } else {
                Configuration::updateValue('facebook_event_addpaymentinfo_active', true);
            }
            
        }

        $helperForm = new HelperForm();
        $helperForm->module = $this;
        $helperForm->name_controller = $this->name;
        $helperForm->token = Tools::getAdminTokenLite('AdminModules');
        $helperForm->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helperForm->title = $this->displayName;
        $helperForm->show_toolbar = true;
        $helperForm->submit_action = 'submit'.$this->name;
        $helperForm->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules')
            ]
        ];
        
        $fieldsForm = array();
        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Facebook Pixel configuration'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Pixel ID'),
                    'name' => 'pixel_id',
                    'placeholder' => 'Enter your Pixel ID',
                    'required' => true
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Use the Facebook Pixel'),
                    'name' => 'is_active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                        ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Use ViewContent event (product page view)'),
                    'name' => 'product_active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Use addToCart event'),
                    'name' => 'addtocart_active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Use InitiateCheckout event (PS v1.7.7.0 or later only)'),
                    'name' => 'initiatecheckout_active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Use AddPaymentInfo event (PS v1.7.7.0 or later only)'),
                    'name' => 'addpaymentinfo_active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Use Purchase event'),
                    'name' => 'purchase_active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Use Contact event'),
                    'name' => 'contact_active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                        ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Use Search event'),
                    'name' => 'search_active',
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helperForm->fields_value = array(
            'pixel_id' => Configuration::get('facebook_pixel_id'),
            'is_active' => Configuration::get('facebook_pixel_active'),
            'product_active' => Configuration::get('facebook_event_contentview_active'),
            'addtocart_active' => Configuration::get('facebook_event_addtocart_active'),
            'purchase_active' => Configuration::get('facebook_event_purchase_active'),
            'contact_active' => Configuration::get('facebook_event_contact_active'),
            'search_active' => Configuration::get('facebook_event_search_active'),
            'initiatecheckout_active' => Configuration::get('facebook_event_initcheckout_active'),
            'addpaymentinfo_active' => Configuration::get('facebook_event_addpaymentinfo_active')
        );
        $_html .= $helperForm->generateForm($fieldsForm);
        $_html .= '<p>Get the latest version on <a href="https://github.com/Adel010/Facebook-Pixel-Prestashop-Free-Module">GitHub</a> | <a href="mailto:adel.alikeche.pro@gmail.com">Contact the developer</a></p>';
        return $_html;

    }

    public function hookDisplayHeader()
    {
        $pid = 0;
        $price = 0;
        $categories = '';
        $order_total = 0;
        $search_str = '';
        if($this->context->controller->getPageName() == "product") {
            $pid = $this->context->smarty->getTemplateVars()['product']['id'];
            $price = $this->context->smarty->getTemplateVars()['product']['price_amount'];
            $cat_arr = $this->context->smarty->getTemplateVars()['breadcrumb']['links'];
            foreach($cat_arr as $cat) {
                $categories .= $cat['title'] . ' > ';
            }
            $categories = substr($categories, 0, (strlen($categories) - 4));
        }
        if($this->context->controller->getPageName() == 'order-confirmation' && Tools::isSubmit('id_order') && Configuration::get('facebook_event_purchase_active')) {
            $order = new Order((int)Tools::getValue('id_order'));
            $order_total = $order->total_paid;
        }
        if(Tools::getValue('controller') === "search") {
            $search_str = Tools::getValue('s');
        }
        if(Configuration::get('facebook_pixel_id') != '' && Configuration::get('facebook_pixel_active')) {
            $this->context->smarty->assign(
                array(
                    'pixel_id' => Configuration::get('facebook_pixel_id'),
                    'view_product' => Configuration::get('facebook_event_contentview_active'),
                    'addtocart' => Configuration::get('facebook_event_addtocart_active'),
                    'page_name' => $this->context->controller->getPageName(),
                    'product_id' => $pid,
                    'product_price' => $price,
                    'cat' => $categories,
                    'order_total' => $order_total,
                    'contact' => Configuration::get('facebook_event_contact_active'),
                    'search' => Configuration::get('facebook_event_search_active'),
                    'search_str' => $search_str,
                    'checkout' => Configuration::get('facebook_event_initcheckout_active'),
                    'paymentinfo' => Configuration::get('facebook_event_addpaymentinfo_active'),
                    'checkout_step' => $this->getCheckoutStepFromContext($this->context)
                )
            );
        }
        return $this->display(__FILE__, 'fb_pixel_script.tpl');        
    }
    public function getCheckoutStepFromContext($context)
    {
        if($context->controller instanceof \OrderControllerCore && method_exists($context->controller, 'getCheckoutProcess')) {
            foreach($context->controller->getCheckoutProcess()->getSteps() as $idStep => $step){
                if($step->isCurrent()) {
                    return $step->getIdentifier();
                }
            }
        }
        return array();
    }
}
