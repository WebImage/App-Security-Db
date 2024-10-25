<?php

namespace WebImage\Security\Db\Services;

use Doctrine\DBAL\Exception;
use WebImage\Core\Dictionary;
use WebImage\Db\ConnectionManager;
use WebImage\Security\DuplicatePermissionException;
use WebImage\Security\Permission;
use WebImage\Security\PermissionProviderInterface;

class PermissionProvider implements PermissionProviderInterface
{
	private ConnectionManager $connectionManager;

	/**
	 * @param ConnectionManager $connectionManager
	 */
	public function __construct(ConnectionManager $connectionManager)
	{
		$this->connectionManager = $connectionManager;
	}

	/**
	 * @throws Exception
	 */
	public function getAll(): array
	{
		$permissions = $this->connectionManager->getConnection()
											   ->createQueryBuilder()
											   ->select('id', 'name')
											   ->from('permissions')
											   ->orderBy('name')
											   ->fetchAllAssociative();

		return array_map(function (array $permission) {
			return new Permission($permission['id'], $permission['name']);
		}, $permissions);
	}

	public function get(string $id): ?Permission
	{
		$permission = $this->connectionManager->createQueryBuilder()
											  ->select('id, name')
											  ->from('permissions')
											  ->where('id = :id')
											  ->setParameter('id', $id)
											  ->executeQuery()
											  ->fetchAssociative();

		if (!$permission) return null;

		return new Permission($permission['id'], $permission['name']);
	}

	public function exists(string $id): bool
	{
		return $this->get($id) !== null;
	}

	/**
	 * @throws Exception
	 */
	public function create(string $id, string $name): Permission
	{
		if ($this->exists($id)) {
			throw new DuplicatePermissionException('Permission already exists: ' . $id);
		}

		$permission = new Permission($id, $name);

		$this->connectionManager->createQueryBuilder()
								->insert('permissions')
								->values(['id' => ':id', 'name' => ':name'])
								->setParameters([
													'id'   => $id,
													'name' => $name
												])
								->executeStatement();

		return $permission;
	}

	/**
	 * @throws Exception
	 */
	public function remove(string $id): bool
	{
		if (!$this->exists($id)) return false;

		$this->connectionManager->createQueryBuilder()
								->from('permissions')
								->where('id = :id')
								->setParameter('id', $id)
								->executeStatement();

		return true;
	}

	public function save(Permission $permission): Permission
	{
		if (!$this->exists($permission->getId())) {
			return $this->create($permission->getId(), $permission->getName());
		}

		$this->connectionManager->createQueryBuilder()
								->from('permissions')
								->where('id = :id')
								->setValue('id', $permission->getId())
								->setValue('name', $permission->getName())
								->executeStatement();

		return $permission;
	}
}