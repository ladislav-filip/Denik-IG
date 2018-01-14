<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/7/2018
 * Time: 16:41
 */

namespace App\AdminModule\Presenters;


use App\AdminModule\Presenters\forms\StockFormFactory;

class StocksPresenter extends BasePresenter
{
    /** @var \App\Model\StocksModel @inject */
    public $stockModel;

    /** @var StockFormFactory */
    private $stockFormFactory;

    /** @var int ID aktuálně vybrané akcie  */
    private $stockId = null;

    public function __construct(StockFormFactory $stockFormFactory)
    {
        $this->stockFormFactory = $stockFormFactory;
    }

    public function renderDefault() {
        $this->template->data = $this->stockModel->loadList();
    }

    public function actionEdit($id = null) {
        $this->stockId = $id;
    }

    public function actionStocksRefresh() {
        $this->stockModel->updatePricesAll();
        $this->redirect('Stocks:');
    }

    public function handleStockRefresh($idx) {
        $data = $this->stockModel->updatePrice($idx);
        $data['updated'] = date('d.m.Y H:i', strtotime($data['updated']));
        $this->payload->data = $data;
        $this->sendPayload();
    }

    protected function createComponentStockEdit() {
        $data = is_null($this->stockId) ? null : $this->stockModel->getById($this->stockId);
        return $this->stockFormFactory->create($data, function($values) {
            $this->stockModel->save($values);
            $this->redirect('Stocks:');
        });
    }
}