<?php
/**
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2020 Adel ALIKECHE
*  @license MIT
*/

class facebookPixelClass extends ObjectModelCore
{
    public $id_facebookpixel;
    public $pixel_id;

    public static $definition = array(
        'table' => 'facebookpixelinstaller',
        'primary' => 'id_facebookpixel',
        'multilang' => false,
        'fields' => array(
            'pixel_id' => array('type' => self::TYPE_INT, 'required' => true)
        )
    ); 
}
