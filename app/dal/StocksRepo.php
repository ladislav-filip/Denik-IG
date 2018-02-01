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

        $sql .= implode(' AND ', $arr);

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
}