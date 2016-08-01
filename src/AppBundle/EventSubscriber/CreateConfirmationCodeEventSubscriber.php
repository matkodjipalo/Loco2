<?php

namespace AppBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\User;

class CreateConfirmationCodeEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'prePersist'
        );
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();
        if (!($entity instanceof User)) {
            return;
        }

        $this->createConfirmationCodeFor($entity);
    }

    private function createConfirmationCodeFor(User $user)
    {
        $confirmationCode = random_bytes(30);
        $user->setConfirmationCode(base64_encode($confirmationCode));
        $user->setRegistrationDt(new \DateTime());
    }
}
