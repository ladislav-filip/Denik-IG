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

    public function create(callable $onSuccess) {
        $form = $this->factory->create();

        $form->addText('code', $this->translate('code'))
            ->setRequired($this->translate("requiredCode"));

        $form->addText('name', $this->translate('name'))
            ->setRequired($this->translate("requiredName"));

        $form->addText('price', $this->translate('price'))
            ->setRequired($this->translate("requiredPrice"));

        $form->addSubmit('send', $this->translate('Send'));

        $form->onSuccess[] = function ($form, $values) use ($onSuccess) {
            $onSuccess($form->getValues(true));
        };

        return $form;
    }

    private function translate($message) {
        return $this->translator->translate("admin.stocks.{$message}");
    }

}