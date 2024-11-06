<?php

namespace WebImage\Security\Db\Entities;

use WebImage\Models\Entities\EntityStub;

trait UserManagedEntityTrait
{
	abstract public function getEntity(): EntityStub;

	public function getCreatedBy(): ?UserEntity
	{
		return $this->getEntity()['createdBy'];
	}

	public function setCreatedBy(UserEntity $createdBy)
	{
		$this->getEntity()['createdBy'] = $createdBy->getEntity();
	}

	public function getUpdatedBy(): ?UserEntity
	{
		return $this->getEntity()['updatedBy'];
	}

	public function setUpdatedBy(UserEntity $updatedBy)
	{
		$this->getEntity()['updatedBy'] = $updatedBy->getEntity();
	}
}