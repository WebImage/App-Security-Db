<?php

namespace WebImage\Security\Db\Entities;

interface UserManagedEntityInterface
{
	public function getCreatedBy(): ?UserEntity;
	public function setCreatedBy(UserEntity $createdBy);
	public function getUpdatedBy(): ?UserEntity;
	public function setUpdatedBy(UserEntity $updatedBy);
}