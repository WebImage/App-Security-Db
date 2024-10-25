<?php

namespace WebImage\Security\Db\Services;

use Doctrine\DBAL\ParameterType;
use WebImage\Core\Dictionary;
use WebImage\Db\ConnectionManager;
use WebImage\Security\Role;
use WebImage\Security\RoleProviderInterface;
use WebImage\Security\SecurityEntityInterface;

class RoleProvider implements RoleProviderInterface
{
	private ConnectionManager $connectionManager;

	/**
	 * @param ConnectionManager $connectionManager
	 */
	public function __construct(ConnectionManager $connectionManager)
	{
		$this->connectionManager = $connectionManager;
	}

	public function get(string $roleId): ?Role
	{
		$role = $this->connectionManager->getConnection()
										->createQueryBuilder()
										->select('id, name')
										->from('roles')
										->where('id = :id')
										->setParameter('id', $roleId)
										->executeQuery()
										->fetchAssociative();

		if (!$role) return null;

		$permissions = $this->permissionsForRoles([$role['id']]);

		return new Role($role['id'], $role['name'], $permissions->has($role['id']) ? $permissions->get($role['id']) : []);
	}

	public function exists(string $role_id): bool
	{
		return false;
	}

	public function getAll(array $limitRoleIds = null): array
	{
		$roles = $this->connectionManager->createQueryBuilder()
										 ->select('id, name')
										 ->from('roles')
										 ->orderBy('name')
										 ->fetchAllAssociative();

		$roleIds = array_map(function (array $role) {
			return $role['id'];
		}, $roles);

		$permissionLookup = $this->permissionsForRoles($roleIds);

		return array_map(function (array $role) use ($permissionLookup) {
			return new Role($role['id'], $role['name'], $permissionLookup->has($role['id']) ? $permissionLookup->get($role['id']) : []);
		}, $roles);
	}

	public function createRoleLookup(array $roleIds): Dictionary
	{
		$lookup = new Dictionary();
		$roles  = $this->getAll($roleIds);
		foreach ($roles as $role) {
			$lookup->set($role->getId(), $role);
		}

		return $lookup;
	}

	public function create(string $role_id, string $name): Role
	{
		$role = new Role($role_id, $name);

		$this->connectionManager->getConnection()
								->createQueryBuilder()
								->insert('roles')
								->values([
											 'id'   => ':id',
											 'name' => ':name'
										 ])
								->setParameters([
													'id'   => $role->getId(),
													'name' => $role->getName()
												])
								->executeStatement();

		return $role;
	}

	public function remove(string $role_id): void
	{
		$this->connectionManager->getConnection()
								->createQueryBuilder()
								->delete('roles')
								->where('id = :id')
								->setParameter('id', $role_id)
								->executeStatement();
	}

	public function save(Role $role): Role
	{
		$this->connectionManager->getConnection()
								->createQueryBuilder()
								->update('roles')
								->set('name', $role->getName())
								->where('id = :id')
								->setParameter('id', $role->getId())
								->executeStatement();

		return $role;
	}

	public function entitiesInRole(string $role_id, int $offset = null, int $limit = null): array
	{
		$entities = $this->connectionManager->getConnection()
											->createQueryBuilder()
											->select('entity')
											->from('entity_roles')
											->where('role_id = :role_id')
											->setParameter('role_id', $role_id)
											->executeQuery()
											->fetchAllAssociative();

		return array_map(function (array $entity) {
			return $entity['entity'];
		}, $entities);
	}

	public function addEntityToRole(SecurityEntityInterface $entity, string $role): void
	{
		$this->connectionManager->getConnection()
								->createQueryBuilder()
								->insert('entity_roles')
								->values([
											 'entity' => ':entity_id',
											 'role_id'   => ':role_id'
										 ])
								->setParameters([
													'entity_id' => $entity->getQId()->toString(),
													'role_id'   => $role
												])
								->executeStatement();
	}

	public function removeEntityFromRole(SecurityEntityInterface $entity, string $role): void
	{
		$this->connectionManager->getConnection()
								->createQueryBuilder()
								->delete('entity_roles')
								->where('entity = :entity_id')
								->andWhere('role_id = :role_id')
								->setParameter('entity_id', $entity->getQId()->toString())
								->setParameter('role_id', $role)
								->executeStatement();
	}

	public function isEntityInRole(SecurityEntityInterface $entity, string $role): bool
	{
		$entityRole = $this->connectionManager->getConnection()
											  ->createQueryBuilder()
											  ->select('entity')
											  ->from('entity_roles')
											  ->where('entity = :entity_id')
											  ->andWhere('role_id = :role_id')
											  ->setParameter('entity_id', $entity->getQId()->toString())
											  ->setParameter('role_id', $role)
											  ->executeQuery()
											  ->fetchOne();

		return (bool)$entityRole;
	}

	public function rolesForEntity(SecurityEntityInterface $entity): array
	{
		$roles = $this->connectionManager->getConnection()
										 ->createQueryBuilder()
										 ->select('role_id')
										 ->from('entity_roles')
										 ->where('entity = :entity_id')
										 ->setParameter('entity_id', $entity->getQId()->toString())
										 ->executeQuery()
										 ->fetchAllAssociative();

		return array_map(function (array $role) {
			return $role['role_id'];
		}, $roles);
	}

	public function rolesForEntities(array $entities): Dictionary
	{
		$lookup = new Dictionary();

		$entityRoles = $this->connectionManager->getConnection()
											   ->createQueryBuilder()
											   ->select('entity, role_id')
											   ->from('entity_roles')
											   ->where('entity IN (:entity_ids)')
											   ->setParameter('entity_ids', array_map(function (SecurityEntityInterface $entity) {
												   return $entity->getQId()->toString();
											   }, $entities))
											   ->executeQuery()
											   ->fetchAllAssociative();

		foreach ($entityRoles as $entityRole) {
			$tRoles   = $lookup->get($entityRole['entity'], []);
			$tRoles[] = $entityRole['role_id'];
			$lookup->set($entityRole['entity'], $tRoles);
		}

		return $lookup;
	}

	public function permissionsForRoles(array $roleIds): Dictionary
	{
		$lookup = new Dictionary();


		$qb          = $this->connectionManager->getConnection()->createQueryBuilder();

		$permissions = $qb->select('role_id, permission_id')
						  ->from('role_permissions')
						  ->where(
							  $qb->expr()->in('role_id', array_map(function () {
								  return '?';
							  }, $roleIds))
						  )
						  ->setParameters(
							  array_map(function ($roleId) {
								  return $roleId;
							  }, $roleIds),
							  array(ParameterType::STRING)
						  )
						  ->executeQuery()
						  ->fetchAllAssociative();

		foreach ($permissions as $permission) {
			$tPermissions   = $lookup->get($permission['role_id'], []);
			$tPermissions[] = $permission['permission_id'];
			$lookup->set($permission['role_id'], $tPermissions);
		}

		return $lookup;
	}

	public function roleHasPermission(string $role, string $permission): bool
	{
		$rolePermission = $this->connectionManager->getConnection()
												  ->createQueryBuilder()
												  ->select('role_id')
												  ->from('role_permissions')
												  ->where('role_id = :role_id')
												  ->andWhere('permission_id = :permission_id')
												  ->setParameter('role_id', $role)
												  ->setParameter('permission_id', $permission)
												  ->executeQuery()
												  ->fetchOne();

		return (bool)$rolePermission;
	}

	public function addPermissionToRole(string $permission, string $role): bool
	{
		if ($this->roleHasPermission($role, $permission)) return false;

		$this->connectionManager->getConnection()
								->createQueryBuilder()
								->insert('role_permissions')
								->values([
											 'role_id'       => ':role_id',
											 'permission_id' => ':permission_id'
										 ])
								->setParameters([
													'role_id'       => $role,
													'permission_id' => $permission
												])
								->executeStatement();

		return true;
	}

	public function removePermissionFromRole(string $permission, string $role): bool
	{
		if (!$this->roleHasPermission($role, $permission)) return false;

		$this->connectionManager->getConnection()
								->createQueryBuilder()
								->delete('role_permissions')
								->where('role_id = :role_id')
								->andWhere('permission_id = :permission_id')
								->setParameter('role_id', $role)
								->setParameter('permission_id', $permission)
								->executeStatement();

		return true;
	}
}