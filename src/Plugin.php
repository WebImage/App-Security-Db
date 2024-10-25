<?php

namespace WebImage\Security\Db;

use WebImage\Application\AbstractPlugin;
use WebImage\Application\ApplicationInterface;
use WebImage\Application\HttpApplication;
use WebImage\Route\ArrayRouteLoader;

class Plugin extends AbstractPlugin
{
	protected function load(ApplicationInterface $app): void
	{
		$this->loadAuthRoutes($app);
	}

	protected function loadAuthRoutes(ApplicationInterface $app): void
	{
//		if (!($app instanceof HttpApplication)) return;
//		else if (!$app->getConfig()->get('webimage/security-db.enableAuthRoutes', false)) return;
//
//		$prefix = $app->getConfig()->get('routes.prefix.auth');
//		$loader = new ArrayRouteLoader();
//		$authRoutes = $loader->load(require(__DIR__ . '/../config/auth-routes.php'));
//		$authRoutes->injectRoutes($app->routes(), $prefix);
	}
}