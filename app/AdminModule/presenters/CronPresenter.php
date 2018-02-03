<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 2/2/2018
 * Time: 18:19
 */

namespace App\AdminModule\Presenters;


use App\Core\AbstractBasePresenter;
use App\DAL\Filters\StockFilter;
use Nette\Utils\DateTime;

class CronPresenter extends AbstractBasePresenter
{

    /** @var \App\Model\StocksModel @inject */
    public $stockModel;

    public function actionStockRefresh() {
        $httpRequest = $this->getHttpRequest();
        // povolené IP adresy odkud je možné spustit úlohu
        $arr = array('::1', '127.0.0.1', '89.185.244.168');
        if (in_array($httpRequest->getRemoteAddress(), $arr)) {
            $filter = new StockFilter();
            // 10 minut
            $filter->toLastUpdated = DateTime::from(-600);
            // pouze automatické aktualizace
            $filter->stockRefreshType = \StockRefreshTypes::Auto;
            // aktualizovat po 50 záznamech
            $filter->limit = 50;

            $count = $this->stockModel->updatePricesAll($filter);
            $this->payload->status = "ok";
            $this->payload->count = $count;
        }
        else {
            $this->payload->status = "fail";
            $this->payload->remoteIp = $httpRequest->getRemoteAddress();
        }
        $this->sendPayload();
    }

}