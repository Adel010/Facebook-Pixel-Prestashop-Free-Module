<?php
/**
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   MIT
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Facebookpixelinstaller extends Module implements PrestaShop\PrestaShop\Core\Module\WidgetInterface
{
    protected $config_form = false;
    private $front_template_file;

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

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->front_template_file = 'module:customshippingcost/views/templates/front/fb_pixel_script.tpl';
    }

    public function install()
    {
        Configuration::updateValue('FACEBOOKPIXELINSTALLER_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        Configuration::deleteByName('FACEBOOKPIXELINSTALLER_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }


    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitFacebookpixelinstallerModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch($this->front_template_file);
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        $pixel_id = "";
        return array(
            'pixel_id' => $pixel_id
        );
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFacebookpixelinstallerModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'FACEBOOKPIXELINSTALLER_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'FACEBOOKPIXELINSTALLER_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'FACEBOOKPIXELINSTALLER_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'FACEBOOKPIXELINSTALLER_LIVE_MODE' => Configuration::get('FACEBOOKPIXELINSTALLER_LIVE_MODE', true),
            'FACEBOOKPIXELINSTALLER_ACCOUNT_EMAIL' => Configuration::get('FACEBOOKPIXELINSTALLER_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'FACEBOOKPIXELINSTALLER_ACCOUNT_PASSWORD' => Configuration::get('FACEBOOKPIXELINSTALLER_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    public function hookDisplayHeader()
    {
        /* Place your code here. */
    }
}
