<?php

return [
	'webimage/security-db' => [
		'enableAuthRoutes' => true
//		'entities' => [
//			'anonymous' => WebImage\Security\Db\Entities\AnonymousEntity::class,
//			'user' => WebImage\Security\Db\Entities\UserEntity::class
//		]
	],
	'routes' => [
		'auth' => [
			'enable' => false,
			'prefix' => '/auth'
		]
//		'prefix' => ['auth' => '/auth']
	],
	// If WebImage/Models plugin is installed then install models from these model files
	'serviceManager' => [
		'providers' => [
			WebImage\Security\Db\Services\SecurityManagerServiceProvider::class
		]
	],
	'webimage/models' => [
		'models' => [
			__DIR__ . '/../models/roles.yml',
			__DIR__ . '/../models/user.yml'
		]
	]
];