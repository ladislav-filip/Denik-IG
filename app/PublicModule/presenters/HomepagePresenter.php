<?php

namespace App\PublicModule\Presenters;


class HomepagePresenter extends BasePresenter
{
	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}
}
