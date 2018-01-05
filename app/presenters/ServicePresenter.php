<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/4/2018
 * Time: 21:34
 */

namespace App\Presenters;

class ServicePresenter extends BasePresenter
{
    /** @var \App\model\DbUpdaterModel @inject */
    public $dbUpdater;

    /** @var \App\model\CacheManagModel @inject */
    public $cacheManag;

    public function renderDefault() {

        $this->template->data = $this->dbUpdater->getDbVersion();
        $this->template->dbname = $this->dbUpdater->getDbName();
        $this->template->tempDir = $this->cacheManag->getTempDir();
    }

    public function actionRefreshCache() {
        $this->cacheManag->clearAll();
        $this->redirect('Service:');
    }

}