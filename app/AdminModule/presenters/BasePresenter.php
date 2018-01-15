<?php

namespace App\AdminModule\Presenters;

use App\Core\AbstractBasePresenter;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends AbstractBasePresenter
{

    protected function beforeRender() {
        if (!$this->getUser()->loggedIn) {
            $this->redirect(':Public:Sign:in');
        }
        parent::beforeRender();
    }
}
