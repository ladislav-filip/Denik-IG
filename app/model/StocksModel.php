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
        $values['price'] = $this->getPrice($data, $code);
        $this->stocksRepo->save($values);
    }

    public function updatePricesAll() {
        $data = $this->stocksRepo->loadList();
        $arr = [];
        $values = [];
        foreach ($data as $d) {
            $arr[] = $d->code;
            $values[] = [
                'id' => $d->id,
                'code' => $d->code,
                'price' => null,
                'updated' => null
            ];
        }
        $prices = $this->alphaVantage->getBatchStockQuotes($arr);

        foreach ($values as $d) {
            $d['price'] = $this->getPrice($prices, $d['code']);
            $this->stocksRepo->save($d);
        }
    }

    public function updatePrice($id) {
        $data = $this->stocksRepo->getById($id);
        $code = $data->code;
        $data = $this->alphaVantage->getBatchStockQuotes([$code]);
        $price = $this->getPrice($data, $code);
        $values = [
            'id' => $id,
            'code' => $code,
            'price' => $price,
            'updated' => null
        ];
        $this->stocksRepo->save($values);
        $data = $this->stocksRepo->getById($id);

        return $data;
    }

    public function getById($id)
    {
        return $this->stocksRepo->getById($id);
    }

    /**
     * Vytáhne z pole dat s cenami tu podle kódu, pokud neexistuje tak vrátí -1
     * @param $data
     * @param $code
     * @return int
     */
    private function getPrice($data, $code) {
        if (isset($data[$code])) {
            return $data[$code];
        }
        else {
            return -1;
        }
    }
}