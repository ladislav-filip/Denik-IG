<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/7/2018
 * Time: 16:42
 */

namespace App\DAL;

use Nette;

class StocksRepo extends AbstractBaseRepo
{
    public function __construct(Nette\Database\Connection $database)
    {
        parent::__construct($database);
    }

    public function loadList() {
        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table}";
        $data = $this->database->query($sql);
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