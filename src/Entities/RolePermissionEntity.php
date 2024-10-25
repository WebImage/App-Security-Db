<?php

namespace WebImage\Security\Db\Entities;

use DateTime;
use WebImage\Models\Services\ModelEntity;

class RolePermissionEntity extends ModelEntity
{
	public function getCreated(): ?DateTime
	{
		return $this->getEntity()['created'];
	}

	public function setCreated(?DateTime $created)
	{
		$this->getEntity()['created'] = $created;
	}

	public function getModified(): ?DateTime
	{
		return $this->getEntity()['modified'];
	}

	public function setModified(?DateTime $modified)
	{
		$this->getEntity()['modified'] = $modified;
	}

	public function getRole(): ?string
	{
		return $this->getEntity()['role'];
	}

	public function setRole(?string $role)
	{
		$this->getEntity()['role'] = $role;
	}

	public function getPermission(): ?string
	{
		return $this->getEntity()['permission'];
	}

	public function setPermission(?string $permission)
	{
		$this->getEntity()['permission'] = $permission;
	}
}