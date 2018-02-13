<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 17:34
 */

namespace App\Model;


use App\Core\UIException;
use App\DAL\RecordsRepo;
use Nette\Utils\DateTime;

class RecordsModel extends AbstractModel
{

    /** @var \App\Model\StocksModel */
    private $stockModel;

    /** @var \App\DAl\RecordsRepo */
    private $recordsRepo;

    public function injectRecordsRepo(RecordsRepo $recordsRepo) {
        $this->recordsRepo = $recordsRepo;
    }

    public function injectStockModel(\App\Model\StocksModel $stockModel) {
        $this->stockModel = $stockModel;
    }

    public function loadList()
    {
        $id = $this->getUser()->getId();
        return $this->recordsRepo->loadList($id);
    }

    public function save($values)
    {
        $stock_id = $values['stock_id'];
        if (empty($values['id'])) {
            unset($values['id']);

            if (!empty($stock_id)) {
                $stock = $this->stockModel->getById($stock_id);
            }
            else {
                $stock_data = array('code' => $values['code'], 'name' => $values['stock_name']);
                $stock_id = $this->stockModel->save($stock_data);
                if ($stock_id === false) throw new UIException($this->translate('stockCodeNotFound', 'messages.error'));
            }
        }

        unset($values['code']);
        unset($values['stock_name']);
        $values['stock_id'] = $stock_id;
        $values['user_id'] = $this->getUser()->getId();
        $values['date_event'] = DateTime::createFromFormat('d.m.Y', $values['date_event']);

        $this->recordsRepo->save($values);
    }

    public function getById($id)
    {
        $result = $this->recordsRepo->getById($id);
        return $result;
    }
}