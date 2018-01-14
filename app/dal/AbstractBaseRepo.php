<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/6/2018
 * Time: 16:07
 */

namespace App\DAL;

use Nette;

abstract class AbstractBaseRepo
{
    /**
     * @var Nette\Database\Connection
     */
    protected $database;

    /**
     * Prefix databázových tabulek
     * @var string
     */
    private static $dbPrefix = 'lf_';

    /**
     * @var int
     */
    private static $dbVersion = null;

    /**
     * AbstractBaseRepo constructor.
     * @param Nette\Database\Connection $database
     */
    public function __construct(Nette\Database\Connection $database)
    {
        $this->database = $database;
    }

    /**
     * Zjistí název připojené databáze
     */
    public function getDbName() {
        $result = $this->database->fetch('select database() as db');
        return $result->db;
    }

    /**
     * Číslo verze databáze
     * @return int
     * @throws \ReflectionException
     */
    public function getDbVersion() {
        if (is_null(self::$dbVersion)) {
            if (!$this->isDbInitialized()) {
                self::$dbVersion = 0;
            } else {
                $sql = "SELECT value FROM lf_settings WHERE 1 = 1 AND (name='db')";
                $value = $this->execScalar($sql);
                self::$dbVersion = intval($value);
            }
        }
        return self::$dbVersion;
    }

    /**
     * Provede zadaný SQL pčíkaz
     * @param $sql
     */
    public function execSql($sql) {
        $this->database->query($sql);
    }

    /**
     * Provede SQL a vrátí jedinou hodnotu
     * @param $sql
     * @param array ...$params
     * @return mixed
     */
    public function execScalar($sql, ...$params) {
        $value = $this->database->fetchField($sql, $params);
        return $value;
    }

    /**
     * Vrátí jedinou hodnotu s tabulky (dle názvu repa) podle podmínky
     * @param $fieldName Název sloupce jehož hodnota se bude vracet
     * @param array ...$params Parametry sloupců pro podmínku (AND)
     * @return mixed
     * @throws \ReflectionException
     */
    protected function execScalarEx($fieldName, ...$params) {
        $table = $this->getTableName();
        $sql = "SELECT {$fieldName} FROM {$table} WHERE 1 = 1 AND ";
        $value = $this->database->fetchField($sql, $params);
        return $value;
    }

    /**
     * Vloží nový anebo aktualizuje existující záznam v tabulce.
     * Předpokládá, že v tabulce je primární klič s názvem ID
     * @param array $assocValues
     * @return mixed|null|string
     * @throws \ReflectionException
     */
    public function save($assocValues = array()) {
        $id = null;
        $table = $this->getTableName();
        // převedu klíče na malá písmena
        $assocValuesCi =array_change_key_case($assocValues, CASE_LOWER);
        // pokud existuje klíč ID, tak jde o update
        if (array_key_exists('id', $assocValuesCi)) {
            $id = $assocValuesCi['id'];
            unset($assocValuesCi[$id]);
            $this->database->query("UPDATE {$table} SET ", $assocValuesCi, "WHERE id = ?", $id);
        }
        else {
            $sql = "INSERT INTO {$table}";
            $this->database->query($sql, $assocValues);
            $id = $this->database->getInsertId();
        }
        return $id;
    }

    public function getById($id) {
        $table = $this->getTableName();
        $sql = "SELECT * FROM {$table} WHERE ";
        $result = $this->database->fetch($sql, ['id' => $id]);
        return $result;
    }

    public function deleteById($id) {
        $table = $this->getTableName();
        $sql = "DELETE FROM {$table} WHERE ";
        $this->database->query($sql, ['id' => $id]);
    }

    /**
     * Název databázové tabulky s prefixem podle názvu repozitáře
     * @return string
     * @throws \ReflectionException
     */
    protected function getTableName() {
        $result = strtolower(str_ireplace('Repo', '',  (new \ReflectionClass($this))->getShortName()));
        return self::$dbPrefix . $result;
    }

    /**
     * Kontrola jestli je databáze již inicializována = jsou tam struktury pro deník
     * @return bool
     */
    private function isDbInitialized() {
        $dbName = $this->getDbName();
        $sql = "SELECT * FROM information_schema.tables WHERE table_schema = '{$dbName}' AND table_name = 'lf_settings' LIMIT 1 ";
        $result = $this->database->fetch($sql);
        return $result !== false;
        return true;
    }
}