<?php

namespace WebImage\Security\Db\Entities;

use DateTime;
use WebImage\Models\Services\ModelEntity;

class RoleEntity extends ModelEntity
{
	public function getAutoLoad(): ?bool
	{
		return $this->getEntity()['autoLoad'];
	}

	public function setAutoLoad(?bool $autoLoad)
	{
		$this->getEntity()['autoLoad'] = $autoLoad;
	}

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