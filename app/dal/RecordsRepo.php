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
        $table = $this->getTableName('records_view');
        $sql = "SELECT * FROM {$table} WHERE";
        $data = $this->database->query($sql, ['user_id' => $userId]);
        return $data;
    }

    public function getById($id) {
        $table = $this->getTableName('records_view');
        $sql = "SELECT * FROM {$table} WHERE ";
        $result = $this->database->fetch($sql, ['id' => $id]);
        return $result;
    }
}