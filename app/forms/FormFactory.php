<?php

namespace App\Forms;

use AlesWita\FormRenderer\BootstrapV4Renderer;
use Nette;
use Nette\Application\UI\Form;


class FormFactory
{
	use Nette\SmartObject;

	/**
	 * @return Form
	 */
	public function create()
	{
        $form = new Nette\Application\UI\Form;
        $form->setRenderer(new BootstrapV4Renderer);
		//$form = new Form;
		return $form;
	}
}
