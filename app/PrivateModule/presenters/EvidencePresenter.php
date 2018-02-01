<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 17:10
 */

namespace App\PrivateModule\Presenters;


use App\Core\UIException;
use App\PrivateModule\Presenters\forms\RecordFormFactory;

class EvidencePresenter extends BasePresenter
{
    /** @var int ID aktuálně vybraného záznamu  */
    private $recordId = null;

    /** @var \App\Model\RecordsModel @inject */
    public $recordsModel;

    /** @var \App\Model\StocksModel @inject */
    public $stockModel;

    /** @var RecordFormFactory */
    private $recordFormFactory;

    private $detail;

    public function __construct(RecordFormFactory $recordFormFactory)
    {
        $this->recordFormFactory = $recordFormFactory;
    }

    public function renderDefault() {
        $this->template->data = $this->recordsModel->loadList();
    }

    public function actionNew() {
        $this->recordId = null;
    }

    public function actionEdit($id) {
        $this->recordId = $id;
        $this->detail = $this->recordsModel->getById($this->recordId);
        $this->template->detail = $this->detail;
    }

    public function handleSuggestStock($term) {
        $data = $this->stockModel->loadList();
        $result = array();
        foreach ($data as $d) {
            $itm = new \stdClass();
            $itm->value = $d->code;
            $itm->label = "[{$d->code}] $d->name {$d->price},-";
            $itm->name = $d->name;
            $itm->price = $d->price;
            $itm->id = $d->id;
            $result[] = $itm;
        }
        $this->sendJson($result);
        $this->terminate();
    }

    protected function beforeRender()
    {
        $this->template->addFilter('profit', function ($buy, $sale) {
            return ($sale - $buy) / $sale * 100;
        });
    }

    protected function createComponentRecordEdit() {
        $data = is_null($this->recordId) ? null : $this->detail;
        return $this->recordFormFactory->create($data, function($values, $form) {
            try {
                $this->recordsModel->save($values);
            }
            catch (UIException $e) {
                $form->addError($e->getMessage());
                return;
            }
            $this->redirect('Evidence:');
        });
    }

}