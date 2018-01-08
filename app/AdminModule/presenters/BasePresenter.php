<?php

namespace App\AdminModule\Presenters;

use Nette;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
    public $locale;

    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;

    protected function beforeRender() {
        if (!$this->getUser()->loggedIn) {
            $this->redirect(':Public:Sign:in');
        }
        parent::beforeRender();
    }
}
