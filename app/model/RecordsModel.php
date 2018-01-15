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

    public function injectStockModel(\App\Model\StocksModel $stockModel) {
        $this->stockModel = $stockModel;
    }

    public function loadList()
    {
        $id = $this->user->getId();
        return $this->recordsRepo->loadList($id);
    }

    public function save($values)
    {
        if (empty($values['id'])) {
            unset($values['id']);
        }

        $stock = $this->stockModel->getByCode($values['code']);
        unset($values['code']);
        $values['stock_id'] = $stock->id;
        $values['user_id'] = $this->user->getId();

        $this->recordsRepo->save($values);
    }
}