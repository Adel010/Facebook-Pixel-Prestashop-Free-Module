<?php
/**
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   https://opensource.org/licenses/GPL-3.0  GNU General Public License version 3
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
        $this->version = '0.0.1';
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
            $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('FACEBOOKPIXELINSTALLER_LIVE_MODE');

        return parent::uninstall() &&
        Configuration::deleteByName('facebook_pixel_id') &&
        Configuration::deleteByName('facebook_pixel_active');
    }


    public function getContent()
    {
        $_html = '';
        if (((bool)Tools::isSubmit('submit'.$this->name)) == true) {
            
            Configuration::updateValue('facebook_pixel_id', Tools::getValue('pixel_id'));
            if(Tools::getValue('is_active') == 0) {
                Configuration::updateValue('facebook_pixel_active', false);
            } else {
                Configuration::updateValue('facebook_pixel_active', true);
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
                'title' => $this->l('Facebook Pixel ID'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Pixel ID'),
                    'name' => 'pixel_id',
                    'required' => true
                ],
                [
                    'type' => 'radio',
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
                ]
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helperForm->fields_value = array(
            'pixel_id' => Configuration::get('facebook_pixel_id'),
            'is_active' => Configuration::get('facebook_pixel_active')
        );
        $_html .= $helperForm->generateForm($fieldsForm);
        $_html .= '<p>Get the latest version on <a href="https://github.com/Adel010/Facebook-Pixel-Prestashop-Free-Module">GitHub</a> | <a href="mailto:adel.alikeche.pro@gmail.com">Contact the developer</a></p>';
        return $_html;

    }

    public function hookDisplayHeader()
    {
        if(Configuration::get('facebook_pixel_id') != '' && Configuration::get('facebook_pixel_active')) {
            $this->context->smarty->assign(
                array(
                    'pixel_id' => Configuration::get('facebook_pixel_id'),
                )
            );
        }
        return $this->display(__FILE__, 'fb_pixel_script.tpl');        
    }
}
