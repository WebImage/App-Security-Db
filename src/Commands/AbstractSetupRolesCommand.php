<?php

namespace WebImage\Security\Db\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WebImage\Application\AbstractCommand;
use WebImage\Core\ArrayHelper;
use WebImage\Security\SecurityManager;

abstract class AbstractSetupRolesCommand extends AbstractCommand
{
	const MAX_PERMISSION_LENGTH = 30;
	const MAX_ROLE_LENGTH = 30;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->sanityChecks();
		$this->createPermissions();
		$this->createRoles();
		return 0;
	}

	protected function configure()
	{
		$this->setName('roles:setup');
		$this->setDescription('Setup roles and permissions');
	}

	private function createPermissions(): void
	{
		$permissions = $this->getSecurityManager()->permissions();

		foreach($this->getRequiredPermissions() as $permissionId => $name) {
			if ($permissions->exists($permissionId)) continue;
			$permissions->create($permissionId, $name);
		}
	}

	private function createRoles(): void
	{
		$roles = $this->getSecurityManager()->roles();

		foreach($this->getRequiredRoles() as $roleId => $info) {
			$role = $roles->get($roleId);

			if ($role === null) {
				$role = $roles->create($roleId, $info['name']);
			}

			foreach($info['permissions'] as $permission) {
				if (!$roles->roleHasPermission($roleId, $permission)) {
					$roles->addPermissionToRole($permission, $roleId);
				}
			}
		}
	}

	/**
	 * Ensure that roles and permission meet all required criteria before creating
	 * @return void
	 * @throws \Exception
	 */
	private function sanityChecks(): void
	{
		$this->assertRoleAndPermissionLengths();
		$this->assertValidRoleDefinitions();
		$this->assertValidAssignedPermissions();
	}

	/**
	 * Ensure that all roles and permissions to be created are within their allowable string lengths
	 * @return void
	 * @throws \Exception
	 */
	private function assertRoleAndPermissionLengths(): void
	{
		foreach($this->getRequiredRoles() as $role => $info) {
			if (strlen($role) > self::MAX_ROLE_LENGTH) {
				throw new \Exception('Role name is too long: ' . $role);
			}
		}

		foreach($this->getRequiredPermissions() as $permission => $description) {
			if (strlen($permission) > self::MAX_PERMISSION_LENGTH) {
				throw new \Exception('Permission name is too long: ' . $permission);
			}
		}
	}

	private function assertValidRoleDefinitions(): void
	{
		foreach($this->getRequiredRoles() as $role => $info) {
			ArrayHelper::assertKeys($info, $role, ['name', 'permissions']);
			if (!is_string($info['name']) || strlen($info['name']) == 0) {
				throw new \Exception('Role name for ' . $role . ' must be a non-empty string');
			}
			// Make sure $info['permission'] is an array of strings
			if (is_array($info['permissions'])) {
				ArrayHelper::assertItemTypes($info['permissions'], 'string');
			} else {
				throw new \Exception('Permissions for role ' . $role . ' must be an array of strings');
			}
		}
	}

	/**
	 * Ensure that all permissions to be added to roles are defined
	 * @return void
	 */
	private function assertValidAssignedPermissions(): void
	{
		foreach($this->getRequiredRoles() as $role => $info) {
			foreach($info['permissions'] as $permission) {
				if (!array_key_exists($permission, $this->getRequiredPermissions())) {
					throw new \Exception('Permission ' . $permission . ' is not defined (assigned to role ' . $role . ')');
				}
			}
		}
	}

	/**
	 * Return the required roles in the format:
	 * @example
	 * [
	 *    'role_id' => ['name' => 'Role Name', 'permissions' => ['permission_id', 'permission_id']]
	 * ]
	 * @return array
	 */
	abstract protected function getRequiredRoles(): array;

	/**
	 * Return the required permissions in the format:
	 * @example
	 * [
	 *   'permission_id' => 'Permission Name'
	 * ]
	 * @return array
	 */
	abstract protected function getRequiredPermissions(): array;

	private function getSecurityManager(): SecurityManager
	{
		return $this->getContainer()->get(SecurityManager::class);
	}

}