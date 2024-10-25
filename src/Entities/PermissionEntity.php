<?php

namespace WebImage\Security\Db\Entities;

use DateTime;
use WebImage\Models\Services\ModelEntity;

class PermissionEntity extends ModelEntity
{
	public function getCreated(): ?DateTime
	{
		return $this->getEntity()['created'];
	}

	public function setCreated(?DateTime $created)
	{
		$this->getEntity()['created'] = $created;
	}

	public function getId(): ?string
	{
		return $this->getEntity()['id'];
	}

	public function setId(?string $id)
	{
		$this->getEntity()['id'] = $id;
	}

	public function getModified(): ?DateTime
	{
		return $this->getEntity()['modified'];
	}

	public function setModified(?DateTime $modified)
	{
		$this->getEntity()['modified'] = $modified;
	}

	public function getName(): ?string
	{
		return $this->getEntity()['name'];
	}

	public function setName(?string $name)
	{
		$this->getEntity()['name'] = $name;
	}
}