<?php
/**
 * Created by PhpStorm.
 * User: ladis
 * Date: 2/1/2018
 * Time: 17:22
 */

namespace App\Model;


abstract class AbstractModel
{
    /** @var \Kdyby\Translation\Translator */
    private $translator;

    /** @var \Nette\Security\User */
    private $user;

    public function injectTranslator(\Kdyby\Translation\Translator $translator) {
        $this->translator = $translator;
    }

    public function injectUser(\Nette\Security\User $user) {
        $this->user = $user;
    }

    /**
     * @return \Nette\Security\User
     */
    protected function getUser() {
        return $this->user;
    }

    /**
     * @param $message
     * @param null $ns
     * @return \Latte\Runtime\IHtmlString|\Nette\Utils\IHtmlString|string
     */
    protected function translate($message, $ns = null) {
        if (is_null($ns)) {
            return $this->translator->translate("messages.ig.{$message}");
        }
        else {
            return $this->translator->translate("{$ns}.{$message}");
        }
    }
}