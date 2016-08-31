<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CreateConfirmationCodeEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
        );
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!($entity instanceof User)) {
            return;
        }

        $this->createConfirmationCodeFor($entity);
    }

    /**
     * Generira konfirmacijski kod koji služi korisniku
     * da potvrdi svoj račun preko emaila.
     *
     * @param User $user
     */
    private function createConfirmationCodeFor(User $user)
    {
        $confirmationCode = random_bytes(30);
        $user->setConfirmationCode(base64_encode($confirmationCode));
        $user->setRegistrationDt(new \DateTime());
    }
}
