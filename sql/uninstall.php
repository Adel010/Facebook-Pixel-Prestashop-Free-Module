<?php
/**
*  @author    Adel ALIKECHE <adel.alikeche.pro@gmail.com>
*  @copyright 2021 Adel ALIKECHE
*  @license   MIT
*/

$sql = array();

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
