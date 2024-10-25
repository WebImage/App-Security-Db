<?php

return [
	'/login' => ['POST' => 'WebImage\Security\Db\Controllers\AuthController@login'],
	'/logout' => 'WebImage\Security\Db\Controllers\AuthController@logout'
];