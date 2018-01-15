<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 17:10
 */

namespace App\PrivateModule\Presenters;


class EvidencePresenter extends BasePresenter
{
    /** @var \App\Model\RecordsModel @inject */
    public $recordsModel;

    public function renderDefault() {
        $this->template->data = $this->recordsModel->loadList();
    }

}