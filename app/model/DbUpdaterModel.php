<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/4/2018
 * Time: 21:40
 */

namespace App\Model;

use Nette;
use Nette\Utils\Finder;

class DbUpdaterModel {

    private $database;

    private $appDir;

    public function __construct($appDir, Nette\Database\Connection $database)
    {
        $this->appDir = $appDir;
        $this->database = $database;
    }

    private function isDbInitialized() {
        $dbName = $this->getDbName();
        $sql = "SELECT * FROM information_schema.tables WHERE table_schema = '{$dbName}' AND table_name = 'lf_settings' LIMIT 1 ";
        $result = $this->database->fetch($sql);
        return $result !== false;
    }

    private function getSqlUpdates($dir, $lastIdx) {
        $arr = [];
        foreach (Finder::findFiles('*.sql')->in($dir) as $key => $file) {
            $idx = str_ireplace('.sql', '', $file->getFilename());
            if (intval($idx) > $lastIdx) {
                $arr[$idx] = $key;
            }
        }
        ksort($arr);
        return $arr;
    }

    private function setVersionDb($ver) {
        $this->database->query('INSERT INTO lf_settings', ['name' => 'db', 'value' => $ver]);
    }

    public function getDbName() {
        $result = $this->database->fetch('select database() as db');
        return $result->db;
    }

    public function getDbVersion() {
        if (!$this->isDbInitialized()) {
            return 0;
        }
        else {
            $value = $this->database->fetchField('SELECT value FROM lf_settings WHERE name = ?', 'db');
            return intval($value);
        }
    }

    public function updateDb() {
        $dir = $this->appDir . '/sql';
        $lastVer = $this->getDbVersion();
        $arr = $this->getSqlUpdates($dir, $lastVer);

        foreach ($arr as $sqlFile) {
            $sql = file_get_contents($sqlFile);
            $this->database->query($sql);
            $this->setVersionDb(++$lastVer);
        }

        return var_export($arr, true);
    }

}