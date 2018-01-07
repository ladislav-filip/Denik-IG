<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/7/2018
 * Time: 16:51
 */

namespace App\Model;


use App\DAL\StocksRepo;

class StocksModel
{

    /**
     * @var StocksRepo
     */
    private $stocksRepo;

    /** @var \App\Model\AlphaVantage */
    private $alphaVantage;

    public function injectStocksRepo(StocksRepo $stocksRepo) {
        $this->stocksRepo = $stocksRepo;
    }

    public function injectAlphaVantage(\App\Model\AlphaVantage $alphaVantage) {
        $this->alphaVantage = $alphaVantage;
    }

    public function loadList() {
        return $this->stocksRepo->loadList();
    }

    public function save($values)
    {
        $code = $values['code'];
        $data = $this->alphaVantage->getBatchStockQuotes([$code]);
        $values['price'] = $data[$code];
        $this->stocksRepo->save($values);
    }

}