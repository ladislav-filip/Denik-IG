<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 17:10
 */

namespace App\PrivateModule\Presenters;


use App\PrivateModule\Presenters\forms\RecordFormFactory;

class EvidencePresenter extends BasePresenter
{
    /** @var int ID aktuálně vybraného záznamu  */
    private $recordId = null;

    /** @var \App\Model\RecordsModel @inject */
    public $recordsModel;

    /** @var RecordFormFactory */
    private $recordFormFactory;

    public function __construct(RecordFormFactory $recordFormFactory)
    {
        $this->recordFormFactory = $recordFormFactory;
    }

    public function renderDefault() {
        $this->template->data = $this->recordsModel->loadList();
    }

    public function actionEdit($id = null) {
        $this->recordId = $id;
    }

    protected function beforeRender()
    {
        $this->template->addFilter('profit', function ($buy, $sale) {
            return ($sale - $buy) / $sale * 100;
        });
    }

    protected function createComponentRecordEdit() {
        $data = is_null($this->recordId) ? null : $this->recordsModel->getById($this->recordId);
        return $this->recordFormFactory->create($data, function($values) {
            $this->recordsModel->save($values);
            $this->redirect('Evidence:');
        });
    }

}