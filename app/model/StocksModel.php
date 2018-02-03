<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/7/2018
 * Time: 16:51
 */

namespace App\Model;


use App\Core\UIException;
use App\DAL\Filters\StockFilter;
use App\DAL\StocksRepo;

class StocksModel extends AbstractModel
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

    public function loadList(StockFilter $filter = null) {
        return $this->stocksRepo->loadList($filter);
    }

    public function save($values)
    {
        if (empty($values['id'])) {
            unset($values['id']);
        }
        $code = $values['code'];
        $data = $this->alphaVantage->getBatchStockQuotes([$code]);
        if (count($data)) {
            $values['price'] = $this->getPrice($data, $code);
            if (empty($values['name'])) $values['name'] = $code;
            $id = $this->stocksRepo->save($values);
            return $id;
        }
        return false;
    }

    private function loadPrices(StockFilter $filter = null) {
        $data = $this->stocksRepo->loadList($filter);
        $arr = [];
        $values = [];
        foreach ($data as $d) {
            $arr[] = $d->code;
            $values[] = [
                'id' => $d->id,
                'code' => $d->code,
                'price' => null,
                'refresh_type' => null,
                'updated' => null
            ];
        }

        if (count($values) > 0) {
            $result = new \stdClass();
            $result->codes = $arr;
            $result->values = $values;
            $result->prices = $this->alphaVantage->getBatchStockQuotes($arr);
            return $result;
        }
        return false;
    }

    public function updatePricesAll(StockFilter $filter = null) {
        $result = 0;

        while ($data = $this->loadPrices($filter)) {
            foreach ($data->values as $d) {
                $d['price'] = $this->getPrice($data->prices, $d['code']);
                $d['refresh_type'] = $d['price'] === -1 ? \StockRefreshTypes::None : \StockRefreshTypes::Auto;
                $this->stocksRepo->save($d);
                $result++;
            }
        }

        return $result;
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
            'refresh_type' => $price === -1 ? \StockRefreshTypes::None : \StockRefreshTypes::Auto,
            'updated' => null
        ];
        $this->stocksRepo->save($values);
        $data = $this->stocksRepo->getById($id);

        return $data;
    }

    public function delete($id)
    {
        if ($this->stocksRepo->isStockUsed($id)) throw new UIException($this->translate('stockNoDeleteIsUsed', 'messages.error'));
        $this->stocksRepo->deleteById($id);
    }

    public function getById($id)
    {
        return $this->stocksRepo->getById($id);
    }

    public function getByCode($code)
    {
        return $this->stocksRepo->getByCode(strtoupper($code));
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