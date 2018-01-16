<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/16/2018
 * Time: 17:43
 */

namespace App\Core\Latte;


class ProfitFilter
{

    /**
     * @param $buy Nákup
     * @param $sale Prodej
     * @return float|int
     */
    public function __invoke($buy, $sale)
    {
        return ($sale - $buy) / $sale * 100;
    }

}