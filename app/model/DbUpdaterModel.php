<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/4/2018
 * Time: 21:40
 */

namespace App\Model;

use Nette;

class DbUpdaterModel {

    private $database;

    public function __construct(Nette\Database\Connection $database)
    {
        $this->database = $database;
    }

    private function isDbInitialized() {
        $dbName = $this->getDbName();
        $sql = "SELECT * FROM information_schema.tables WHERE table_schema = '{$dbName}' AND table_name = 'lf_settings' LIMIT 1 ";
        $result = $this->database->fetch($sql);
        return $result !== false;
    }

    public function getDbName() {
        $result = $this->database->fetch('select database() as db');
        return $result->db;
    }

    public function getDbVersion() {
        if (!$this->isDbInitialized()) {
            return 0;
        }
    }

}