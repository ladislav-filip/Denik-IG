<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 1/15/2018
 * Time: 16:52
 */

namespace App\Core;

use Nette;

/**
 * Abstraktní třída pro všechny presentery
 * Class AbstractBasePresenter
 * @package App\Core
 */
class AbstractBasePresenter extends Nette\Application\UI\Presenter
{
    /** @persistent */
    public $locale;

    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;

}