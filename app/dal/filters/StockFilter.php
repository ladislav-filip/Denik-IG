<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 2/1/2018
 * Time: 20:25
 */

namespace App\DAL\Filters;

class StockFilter
{
    /**
     * Počet záznamů, který se má načíst
     * @var int
     */
    public $limit;

    /**
     * Vyhledává v obsahu názvu a podle počátku v kódu
     * @var string
     */
    public $fulltext;

    /**
     * Výběr všeho co bylo aktualizováno před tímto datem/časem
     * @var \Nette\Utils\DateTime
     */
    public $toLastUpdated;

    /**
     * Typ aktualuzace podle které se bude hledat
     * @var int
     */
    public $stockRefreshType;
}