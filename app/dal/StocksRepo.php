<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/7/2018
 * Time: 16:42
 */

namespace App\DAL;

use App\DAL\Filters\StockFilter;
use Nette;

class StocksRepo extends AbstractBaseRepo
{
    public function __construct(Nette\Database\Connection $database)
    {
        parent::__construct($database);
    }

    public function loadList(StockFilter $filter = null) {
        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} ";

        $arr = array('WHERE 1 = 1 ');
        $prm = array();

        if (!empty($filter->fulltext)) {
            $arr[] = ' (name like ? OR code like ?)';
            $prm[] = "%{$filter->fulltext}%";
            $prm[] = "%{$filter->fulltext}";
        }

        if (!empty($filter->toLastUpdated)) {
            $arr[] = ' updated < ? ';
            $prm[] = $filter->toLastUpdated;
        }

        if (isset($filter->stockRefreshType) && \StockRefreshTypes::IsEnum($filter->stockRefreshType) ) {
            $arr[] = "refresh_type = {$filter->stockRefreshType}";
        }

        $sql .= implode(' AND ', $arr);
        $sql .= ' ORDER BY code';

        if (isset($filter->limit) && intval($filter->limit) > 0) {
            $sql .= ' LIMIT ' . intval($filter->limit);
        }

        $data = $this->database->queryArgs($sql, $prm);
        return $data;
    }

    public function getByCode($code)
    {
        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE";
        $data = $this->database->fetch($sql, ['code' => $code]);
        return $data;
    }

    /**
     * Kontrola zda je daná akcie již někde použita, např. v deníku
     * @param $id
     * @return bool
     * @throws \ReflectionException
     */
    public function isStockUsed($id) {
        $tblRec = $this->getTableName('records');
        $sql = "SELECT count(*) as pocet FROM {$tblRec} WHERE ";
        $data = $this->database->fetchField($sql, ['stock_id' => $id]);
        return $data > 0;
    }
}