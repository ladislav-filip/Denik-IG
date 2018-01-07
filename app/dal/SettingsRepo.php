<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/6/2018
 * Time: 16:10
 */

namespace App\DAL;

use Nette;

class SettingsRepo extends AbstractBaseRepo
{
    public function __construct(Nette\Database\Connection $database)
    {
        parent::__construct($database);
    }

    public function getByName($name) {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE name = ?';
        $result = $this->database->fetch($sql, $name);
        return $result;
    }
}