<?php
/**
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   MIT
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'facebookpixelinstaller` (
    `id_facebookpixelinstaller` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY  (`id_facebookpixelinstaller`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
