<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 17:54
 */

namespace App\PrivateModule\Presenters\forms;

use Nette\Application\UI\Form;
use App\Forms\FormFactory;

class RecordFormFactory
{
    /** @var FormFactory */
    private $factory;

    /** @var \Kdyby\Translation\Translator */
    private $translator;

    public function __construct(FormFactory $factory, \Kdyby\Translation\Translator $translator)
    {
        $this->factory = $factory;
        $this->translator = $translator;
    }

    public function create($data, callable $onSuccess) {
        $form = $this->factory->create();

        $form->addHidden('id');

        $form->addText('code', $this->translate('StockCode', 'messages.ig'))
            ->setRequired($this->translate("requiredCode"))
            ->addRule($form::PATTERN, $this->translate('StockCodeRule', 'admin.stocks'), '[A-Za-z0-9]{1,10}');

        $form->addText('amount', $this->translate('Amount', 'messages.ig'))
            ->setRequired($this->translate('requiredAmount'))
            ->addRule(Form::INTEGER, $this->translate('ValueMustNumber', 'messages.ig'))
            ->addRule(Form::RANGE, $this->translate('StockAmountRange', 'admin.stocks'), array(1,99999));

        $form->addText('price', $this->translate('PricePerPiece', 'messages.ig'))
            ->setRequired($this->translate('requiredPrice'))
            ->addRule(Form::FLOAT, $this->translate('ValueMustNumber', 'messages.ig'));

        $form->addSubmit('send', $this->translate('Save', 'messages.ig'));

        $form->addButton('cancel', $this->translate('Cancel', 'messages.ig'))
            ->setHtmlAttribute('onclick', 'window.history.back();');

        $form->onSuccess[] = function ($form, $values) use ($onSuccess) {
            $values = $form->getValues(true);
            unset($values['cancel']);
            $values['code'] = strtoupper($values['code']);
            $onSuccess($values);
        };

        if (!is_null($data)) {
            $form->setDefaults([
                'id' => $data->id,
                'code' => $data->code
            ]);
        }

        return $form;
    }

    private function translate($message, $ns = null) {
        if (is_null($ns)) {
            return $this->translator->translate("private.evidence.{$message}");
        }
        else {
            return $this->translator->translate("{$ns}.{$message}");
        }
    }
}