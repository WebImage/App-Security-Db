<?php

namespace WebImage\Security\Db\Entities;

use DateTime;
use WebImage\Models\Services\ModelEntity;

class EntityRoleEntity extends ModelEntity
{
	public function getCreated(): ?DateTime
	{
		return $this->getEntity()['created'];
	}

	public function setCreated(?DateTime $created)
	{
		$this->getEntity()['created'] = $created;
	}

	public function getEntityId(): ?string
	{
		return $this->getEntity()['entity'];
	}

	public function setEntityId(?string $entityId)
	{
		$this->getEntity()['entity'] = $entityId;
	}

	public function getRole(): ?string
	{
		return $this->getEntity()['role'];
	}

	public function setRole(?string $role)
	{
		$this->getEntity()['role'] = $role;
	}

	public function getModified(): ?DateTime
	{
		return $this->getEntity()['modified'];
	}

	public function setModified(?DateTime $modified)
	{
		$this->getEntity()['modified'] = $modified;
	}
}