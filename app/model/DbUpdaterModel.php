<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/4/2018
 * Time: 21:40
 */

namespace App\Model;

use App\DAL\SettingsRepo;
use Nette;
use Nette\Utils\Finder;

/**
 * Aktualizace databázového modelu
 * Class DbUpdaterModel
 * @package App\Model
 */
class DbUpdaterModel {

    private $appDir;

    /**
     * @var SettingsRepo
     */
    private $settingsRepo;

    public function __construct($appDir)
    {
        $this->appDir = $appDir;
    }

    public function injectSettingsRepo(SettingsRepo $settingsRepo) {
        $this->settingsRepo = $settingsRepo;
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

    /**
     * Název databáze
     */
    public function getDbName() {
        return $this->settingsRepo->getDbName();
    }

    /**
     * Aktuální verze DB
     * @return int
     * @throws \ReflectionException
     */
    public function getDbVersion() {
        return $this->settingsRepo->getDbVersion();
    }

    /**
     * Aktualizace DB struktur dle přiložených SQL skriptů
     * @return mixed
     * @throws \ReflectionException
     */
    public function updateDb() {
        $dir = $this->appDir . '/sql';
        $lastVer = $this->getDbVersion();
        $arr = $this->getSqlUpdates($dir, $lastVer);
        $values = ['name' => 'db', 'value' => 0];

        if ($lastVer > 0) {
            $data = $this->settingsRepo->getByName('db');
            $values['id'] = $data->id;
        }

        foreach ($arr as $sqlFile) {
            $sql = file_get_contents($sqlFile);
            $this->settingsRepo->execSql($sql);
            $values['value'] = ++$lastVer;
            $values['id'] = $this->settingsRepo->save($values);
        }
        return var_export($arr, true);
    }

}