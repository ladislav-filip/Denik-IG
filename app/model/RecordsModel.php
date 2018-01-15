<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 17:34
 */

namespace App\Model;


use App\DAL\RecordsRepo;

class RecordsModel
{

    /** @var \App\Model\StocksModel */
    private $stockModel;

    /** @var \App\DAl\RecordsRepo */
    private $recordsRepo;

    /** @var \Nette\Security\User */
    private $user;

    public function injectUser(\Nette\Security\User $user) {
        $this->user = $user;
    }

    public function injectRecordsRepo(RecordsRepo $recordsRepo) {
        $this->recordsRepo = $recordsRepo;
    }

    public function loadList()
    {
        $id = $this->user->getId();
        return $this->recordsRepo->loadList($id);
    }
}