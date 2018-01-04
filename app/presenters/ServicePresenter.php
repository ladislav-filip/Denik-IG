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

    public function renderDefault() {

        $this->template->data = $this->dbUpdater->getDbVersion();
    }

}