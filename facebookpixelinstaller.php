<?php
/**
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   MIT
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(_PS_MODULE_DIR_ . "facebookpixelinstaller/facebookPixelClass.php");

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
        if (((bool)Tools::isSubmit('submit'.$this->name)) == true) {
            
            $sql_pixel = 'SELECT * FROM ' . _DB_PREFIX_ . 'facebookpixelinstaller';
            $query_result = Db::getInstance()->executeS($sql_pixel);

            if(count($query_result) == 0) {
                $pixel = new facebookPixelClass();
                $pixel->pixel_id = Tools::getValue('pixel_id');
                $pixel->save();
            } else {
                $pixel = new facebookPixelClass(1);
                $pixel->pixel_id = Tools::getValue('pixel_id');
                $pixel->save();
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
                ]
            ]
        ];

        $sql_pixel = 'SELECT * FROM ' . _DB_PREFIX_ . 'facebookpixelinstaller';
        $query_result = Db::getInstance()->executeS($sql_pixel);

        if(count($query_result) > 0) {
            $pixel_id = $query_result[0]['pixel_id'];
            $helperForm->fields_value = array(
                'pixel_id' => $pixel_id
            );
        }

        return $helperForm->generateForm($fieldsForm);

    }

    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        return $this->fetch($this->front_template_file);
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        $pixel_id = "";
        $sql_pixel = 'SELECT * FROM ' . _DB_PREFIX_ . 'facebookpixelinstaller';
        $query_result = Db::getInstance()->executeS($sql_pixel);

        if(count($query_result) > 0) {
            $pixel_id = $query_result[0]['pixel_id'];
        }
        return array(
            'pixel_id' => $pixel_id
        );
    }

    public function hookDisplayHeader()
    {
        /* Place your code here. */
    }
}
