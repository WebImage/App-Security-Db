<?php

namespace WebImage\Security\Db\Services;

use Exception;
use WebImage\Core\Dictionary;
use WebImage\Db\ConnectionManager;
use WebImage\Security\Db\Entities\UserEntity;
use WebImage\Security\Db\Repositories\UserRepository;
use WebImage\Security\Db\SecurityEntities\UserSecurityEntity;
use WebImage\Security\EntityFactoryInterface;
use WebImage\Security\QId;
use WebImage\Security\SecurityEntityInterface;
use WebImage\Security\SecurityManager;
use WebImage\Security\UnsupportedEntityObjectException;

class UserEntityFactory implements EntityFactoryInterface
{
	private UserRepository $userRepository;

	/**
	 * @param UserRepository $userRepository
	 */
	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	public function entity(SecurityManager $securityManager, object $user, string $namespace): ?SecurityEntityInterface
	{
		if (!($user instanceof UserEntity)) throw new UnsupportedEntityObjectException('UserEntityFactory only supports UserEntity objects');

		return new UserSecurityEntity($securityManager, new QId($namespace, $user->getId()), $user);
	}

	public function get(SecurityManager $securityManager, QId $qid): ?SecurityEntityInterface
	{
		die(__FILE__ . ':' . __LINE__ . '<br />' . PHP_EOL);
		// TODO: Implement get() method.
	}

	public function getMultiple(SecurityManager $securityManager, array $qids): Dictionary
	{
		die(__FILE__ . ':' . __LINE__ . '<br />' . PHP_EOL);
		// TODO: Implement getMultiple() method.
	}
}