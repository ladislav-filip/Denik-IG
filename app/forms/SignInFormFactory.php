<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;

    /** @var \Kdyby\Translation\Translator */
    private $translator;

	public function __construct(FormFactory $factory, User $user, \Kdyby\Translation\Translator $translator)
	{
		$this->factory = $factory;
		$this->user = $user;
		$this->translator = $translator;
	}


	/**
	 * @return Form
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();

		$form->addText('username', $this->translate('Username'))
			->setRequired($this->translate("requiredUsername"));

		$form->addPassword('password', $this->translate('Password'))
            ->setRequired($this->translate("requiredPassword"));

		$form->addCheckbox('remember', $this->translate('KeepSignIn'));

		$form->addSubmit('send', $this->translate('SignIn'));

		$form->onSuccess[] = function (Form $form, $values) use ($onSuccess) {
			try {
				$this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
				$this->user->login($values->username, $values->password);
			} catch (Nette\Security\AuthenticationException $e) {
				$form->addError($this->translate('errorUsernameOrPassword'));
				return;
			}
			$onSuccess();
		};

		return $form;
	}

	private function translate($message) {
	    return $this->translator->translate("messages.sign.{$message}");
    }
}
