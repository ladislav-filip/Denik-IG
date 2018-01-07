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

    public function injectStocksRepo(StocksRepo $stocksRepo) {
        $this->stocksRepo = $stocksRepo;
    }

    public function loadList() {
        return $this->stocksRepo->loadList();
    }

    public function save($values)
    {
        $this->stocksRepo->save($values);
    }

}