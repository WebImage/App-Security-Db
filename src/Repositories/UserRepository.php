<?php

namespace WebImage\Security\Db\Repositories;

use WebImage\Models\Query\FilterBuilder;
use WebImage\Models\Services\ModelRepository;
use WebImage\Models\Services\RepositoryInterface;
use WebImage\Security\Db\Entities\UserEntity;

class UserRepository extends ModelRepository
{
	public function __construct(\WebImage\Models\Services\RepositoryInterface $repo, string $model='user')
	{
		parent::__construct($repo, $model);
	}

	public function get(int $id): ?UserEntity
	{
		$entity = $this->query()->get($id);
		return $entity === null ? null : new UserEntity($entity);
	}

	public function create(): UserEntity
	{
		return new UserEntity($this->createEntity());
	}

	public function getUserByUsername(string $username): ?UserEntity
	{
		$entity = $this->query()->where('username', $username)->get();
		return $entity === null ? null : new UserEntity($entity);
	}

	public function getUserByEmail(string $email): ?UserEntity
	{
		$entity = $this->query()->where('email', $email)->get();
		return $entity === null ? null : new UserEntity($entity);
	}

	public function getUserByUsernameOrEmail(string $username): ?UserEntity
	{
		$entity = $this->query()->buildWhere(function(FilterBuilder $builder) use ($username) {
			$builder->or(function(FilterBuilder $builder) use ($username) {
				$builder->eq('username', $username);
				$builder->eq('email', $username);
			});
		})->get();

		return $entity === null ? null : new UserEntity($entity);
	}

	public function save(UserEntity $userEntity): UserEntity
	{
		$entity = $this->getRepo()->saveEntity($userEntity->getEntity());
		return new UserEntity($entity);
	}
}