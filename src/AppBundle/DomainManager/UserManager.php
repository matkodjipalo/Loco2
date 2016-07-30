<?php

namespace AppBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Event\UserEvents;
use AppBundle\Event\UserEvent;

class UserManager
{
	private $entityManager;
	private $eventDispatcher;

	public function __construct(
		EntityManager $entityManager,
		EventDispatcherInterface $eventDispatcher

	) {
		$this->entityManager = $entityManager;
		$this->eventDispatcher = $eventDispatcher;
	}

	public function createUser(User $user)
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush();

		$this->eventDispatcher->dispatch(
			UserEvents::NEW_USER_CREATED,
			new UserEvent($user)
		);
	}

	public function enableUser(User $user)
	{
		$user->setConfirmationCode(null);
        $user->setIsActive(true);

		$this->entityManager->persist($user);
		$this->entityManager->flush();

		$this->eventDispatcher->dispatch(
			UserEvents::NEW_USER_ENABLED,
			new UserEvent($user)
		);
	}
}