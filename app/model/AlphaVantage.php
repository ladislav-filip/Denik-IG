<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/7/2018
 * Time: 17:51
 */

namespace App\Model;


class AlphaVantage
{
    const URL = 'https://www.alphavantage.co/query';

    private function getApiKey() {
        return 'YSVDN7AVYVZCLU6B';
    }

    public function getBatchStockQuotes($symbols = array()) {
        $querySymbols = implode(',', $symbols);
        $query = "?function=BATCH_STOCK_QUOTES&symbols={$querySymbols}&apikey=" . $this->getApiKey();
        $url = self::URL . $query;
        $json = file_get_contents($url);
        $obj = json_decode($json, true);

        $result = [];
        $stocks = $obj['Stock Quotes'];
        if (count($stocks) > 0) {
            foreach ($stocks as $d) {
                $result[$d['1. symbol']] = floatval($d['2. price']);
            }
        }
        return $result;
    }
}