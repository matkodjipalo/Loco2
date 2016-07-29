<?php

namespace AppBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Util\SecureRandomInterface;
use AppBundle\Entity\User;

class UserManager
{
	private $entityManager;

	public function __construct(
		EntityManager $entityManager
	) {
		$this->entityManager = $entityManager;
	}

	public function createUser(User $user)
	{
		$confirmationCode = random_bytes(30);
		$user->setConfirmationCode(base64_encode($confirmationCode));
		$user->setRegistrationDt(new \DateTime());

		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}
}