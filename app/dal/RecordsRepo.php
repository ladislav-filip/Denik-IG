<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 17:28
 */

namespace App\DAL;

use Nette;

class RecordsRepo extends AbstractBaseRepo
{
    public function __construct(Nette\Database\Connection $database)
    {
        parent::__construct($database);
    }

    public function loadList($userId) {
        $table = $this->getTableName();
        $tableStock = $this->getTableName('stocks');
        $sql = <<<TAG
SELECT a.*, a.amount * a.price as price_all, a.amount * b.price as price_all_now, b.price as price_now, b.code, b.name,
        (a.amount * a.price - a.amount * b.price) as profit
        FROM {$table} a 
        INNER JOIN {$tableStock} b ON a.stock_id = b.id WHERE 
TAG;
        $data = $this->database->query($sql, ['user_id' => $userId]);
        return $data;
    }
}