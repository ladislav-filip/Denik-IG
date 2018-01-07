<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

        $admin = new RouteList('Admin');
        $admin[] = new Route('admin/[<locale=cs cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');
        $router[] = $admin;

        $public = new RouteList('Public');
        //$public[] = new Route('[<locale=cs cs|en>/]<presenter>/<action>[/<id>]', 'Homepage:default');
        $public[] = new Route('[<locale=cs cs|en>/]<presenter>/<action>/<id>', array(
            'presenter' => array(
                Route::VALUE => 'Homepage',
                Route::FILTER_TABLE => array(
                    // řetězec v URL => presenter
                    'produkt' => 'Specialization'
                ),
            ),
            'action' => 'default',
            'id' => NULL,
        ));
        $router[] = $public;

        //$router[] = new Route('[<locale=cs cs|en>/]<presenter>/<action>', "Homepage:default");
		return $router;
	}
}
