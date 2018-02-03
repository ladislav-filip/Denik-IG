<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 2/2/2018
 * Time: 20:22
 */

/**
 * Enum pro typy aktualizace akci9
 * Class RefreshTypes
 */
class StockRefreshTypes
{
    /**
     * Povolené automatické aktualizace
     */
    const Auto = 0;
    /**
     * Pouze manuální aktualizace
     */
    const Manual = 1;
    /**
     * Zakázané aktualizace
     */
    const None = 9;

    /**
     * Validace zda se jedná o enum
     * @param $value
     * @return bool
     */
    public static function IsEnum($value) {
        $arr = array(\StockRefreshTypes::Auto, \StockRefreshTypes::Manual, \StockRefreshTypes::None);
        return in_array($value, $arr);
    }
}