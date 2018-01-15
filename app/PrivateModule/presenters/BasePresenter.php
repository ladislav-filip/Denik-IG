<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 16:47
 */

namespace App\PrivateModule\Presenters;

use App\Core\AbstractBasePresenter;

class BasePresenter extends AbstractBasePresenter
{
    protected function beforeRender() {
        if (!$this->getUser()->loggedIn) {
            $this->redirect(':Public:Sign:in');
        }
        parent::beforeRender();
    }
}