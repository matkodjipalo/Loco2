<?php

namespace AppBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Entity\User;
use AppBundle\Event\UserEvents;
use AppBundle\Event\UserEvent;

class UserManager
{
    /** @param EntityManager */
    private $entityManager;

    /** @param EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(
        EntityManager $entityManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Zapisuje korisnika u bazu,
     * te šalje obavijest pretplatnicima o tome
     *
     * @param  User $user
     */
    public function createUser(User $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->eventDispatcher->dispatch(
            UserEvents::NEW_USER_CREATED,
            new UserEvent($user)
        );
    }

    /**
     * Aktivira korisnika, te šalje obavijest pretplatnicima o tome
     *
     * @param  User $user
     */
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
