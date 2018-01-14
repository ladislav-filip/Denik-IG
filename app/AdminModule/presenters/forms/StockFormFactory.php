<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/7/2018
 * Time: 17:24
 */

namespace App\AdminModule\Presenters\forms;

use App\Forms\FormFactory;

class StockFormFactory
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

        $form->addText('code', $this->translate('code'))
            ->setRequired($this->translate("requiredCode"));

        $form->addText('name', $this->translate('name'))
            ->setRequired($this->translate("requiredName"));

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
                'code' => $data->code,
                'name' => $data->name
            ]);
        }

        return $form;
    }

    private function translate($message, $ns = null) {
        if (is_null($ns)) {
            return $this->translator->translate("admin.stocks.{$message}");
        }
        else {
            return $this->translator->translate("{$ns}.{$message}");
        }
    }

}