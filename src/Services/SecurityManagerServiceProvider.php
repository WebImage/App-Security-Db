<?php

namespace WebImage\Security\Db\Services;

use WebImage\Container\ServiceProvider\AbstractServiceProvider;
use WebImage\Db\ConnectionManager;
use WebImage\Security\EntityFactoryResolver;
use WebImage\Security\PermissionProviderInterface;
use WebImage\Security\RoleProviderInterface;
use WebImage\Security\SecurityManager;

class SecurityManagerServiceProvider extends AbstractServiceProvider
{
	protected array $provides = [
		SecurityManager::class,
		RoleProviderInterface::class,
		PermissionProviderInterface::class,
		EntityFactoryResolver::class
	];

	public function register(): void
	{
		$this->registerSecurityManager();
		$this->registerRoleProvider();
		$this->registerPermissionProvider();
		$this->registerEntityFactoryResolver();
	}

	private function registerSecurityManager()
	{
		$constructor = $this->getContainer()->addShared(SecurityManager::class);
		$constructor->addArguments([
									   RoleProviderInterface::class,
									   PermissionProviderInterface::class,
									   EntityFactoryResolver::class
								   ]);
	}

	private function registerRoleProvider()
	{
		$constructor = $this->getContainer()->addShared(RoleProviderInterface::class, RoleProvider::class);
		$constructor->addArgument(ConnectionManager::class);
	}

	private function registerPermissionProvider()
	{
		$constructor = $this->getContainer()->addShared(PermissionProviderInterface::class, PermissionProvider::class);
		$constructor->addArgument(ConnectionManager::class);
	}

	private function registerEntityFactoryResolver()
	{
		$this->getContainer()->addShared(EntityFactoryResolver::class);
	}
}