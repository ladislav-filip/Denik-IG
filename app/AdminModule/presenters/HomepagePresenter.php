<?php

namespace App\AdminModule\Presenters;


class HomepagePresenter extends BasePresenter
{
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}
}
