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

    public function __construct(StockFormFactory $stockFormFactory)
    {
        $this->stockFormFactory = $stockFormFactory;
    }

    public function renderDefault() {
        $this->template->data = $this->stockModel->loadList();
    }

    public function actionEdit() {

    }

    protected function createComponentStockEdit() {
        return $this->stockFormFactory->create(function($values) {
            $this->stockModel->save($values);
            $this->redirect('Stocks:');
        });
    }
}