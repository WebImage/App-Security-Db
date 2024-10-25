<?php

namespace WebImage\Security\Db\Entities;

use WebImage\Models\Services\ModelEntity;

class UserEntity extends ModelEntity
{
	public function getApproved(): bool
	{
		return (bool)$this->getEntity()['approved'];
	}

	public function setApproved(bool $approved)
	{
		$this->getEntity()['approved'] = $approved;
	}

	public function getApprovedBy(): ?UserEntity
	{
		return $this->getEntity()['approvedBy'];
	}

	public function setApprovedBy(?UserEntity $approvedBy)
	{
		$this->getEntity()['approvedBy'] = $approvedBy;
	}

	public function getComment(): ?string
	{
		return $this->getEntity()['comment'];
	}

	public function setComment(?string $comment)
	{
		$this->getEntity()['comment'] = $comment;
	}

	public function getCreated(): ?\DateTime
	{
		return $this->getEntity()['created'];
	}

	public function setCreated(?\DateTime $created)
	{
		$this->getEntity()['created'] = $created;
	}

	public function getCreatedBy(): ?UserEntity
	{
		return $this->getEntity()['createdBy'];
	}

	public function setCreatedBy(?UserEntity $createdBy)
	{
		$this->getEntity()['createdBy'] = $createdBy;
	}

	public function getEmail(): ?string
	{
		return $this->getEntity()['email'];
	}

	public function setEmail(?string $email)
	{
		$this->getEntity()['email'] = $email;
	}

	public function getEnable(): bool
	{
		return (bool)$this->getEntity()['enable'];
	}

	public function setEnable(bool $enable)
	{
		$this->getEntity()['enable'] = $enable;
	}

	public function getFailedLoginAttempts(): int
	{
		return (int)$this->getEntity()['failedLoginAttempts'];
	}

	public function setFailedLoginAttempts(int $failedLoginAttempts)
	{
		$this->getEntity()['failedLoginAttempts'] = $failedLoginAttempts;
	}

	public function getId(): ?int
	{
		return $this->getEntity()['id'];
	}

public function setId(?int $id)
	{
		$this->getEntity()['id'] = $id;
	}

	public function getLastActivity(): ?\DateTime
	{
		return $this->getEntity()['lastActivity'];
	}

	public function setLastActivity(?\DateTime $lastActivity)
	{
		$this->getEntity()['lastActivity'] = $lastActivity;
	}

	public function getLastLogin(): ?\DateTime
	{
		return $this->getEntity()['lastLogin'];
	}

	public function setLastLogin(?\DateTime $lastLogin)
	{
		$this->getEntity()['lastLogin'] = $lastLogin;
	}

	public function getLastPasswordChanged(): ?\DateTime
	{
		return $this->getEntity()['lastPasswordChanged'];
	}

	public function setLastPasswordChanged(?\DateTime $lastPasswordChanged)
	{
		$this->getEntity()['lastPasswordChanged'] = $lastPasswordChanged;
	}

	public function getPassword(): ?string
	{
		return $this->getEntity()['password'];
	}

	public function setPassword(?string $password)
	{
		$this->getEntity()['password'] = $password;
	}

	public function getUpdated(): ?\DateTime
	{
		return $this->getEntity()['updated'];
	}

	public function setUpdated(?\DateTime $updated)
	{
		$this->getEntity()['updated'] = $updated;
	}

	public function getUpdatedBy(): ?UserEntity
	{
		return $this->getEntity()['updatedBy'];
	}

	public function setUpdatedBy(?UserEntity $updatedBy)
	{
		$this->getEntity()['updatedBy'] = $updatedBy;
	}

	public function getUsername(): ?string
	{
		return $this->getEntity()['username'];
	}

	public function setUsername(?string $username)
	{
		$this->getEntity()['username'] = $username;
	}
}