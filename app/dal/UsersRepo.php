<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/7/2018
 * Time: 12:46
 */

namespace App\DAL;

use Nette;

class UsersRepo extends AbstractBaseRepo
{
    public function __construct(Nette\Database\Connection $database)
    {
        parent::__construct($database);
    }

    public function getByLogin($login) {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE user_login = ?';
        $result = $this->database->fetch($sql, $login);
        return $result;
    }

    /**
     * Název databázové tabulky s prefixem podle názvu repozitáře
     * @return string
     * @throws \ReflectionException
     */
    protected function getTableName() {
        $result = str_ireplace('Repo', '',  (new \ReflectionClass($this))->getShortName());
        return 'ig_' . $result;
    }
}