<?php

namespace WebImage\Security\Db\SecurityEntities;

use WebImage\Security\AbstractSecurityEntity;
use WebImage\Security\Db\Entities\UserEntity;
use WebImage\Security\QId;
use WebImage\Security\SecurityManager;

class UserSecurityEntity extends AbstractSecurityEntity
{
	private UserEntity $user;
	public function __construct(SecurityManager $securityManager, QId $qid, UserEntity $user)
	{
		parent::__construct($securityManager, $qid);
		$this->user = $user;
	}

	public function getUser(): UserEntity
	{
		return $this->user;
	}
}