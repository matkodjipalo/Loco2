<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\UserEvents;
use AppBundle\Event\UserEvent;
use AppBundle\Entity\User;

class SendConfirmationMailListener implements EventSubscriberInterface
{
	private $mailer;

	public function __construct(\Swift_Mailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public static function getSubscribedEvents()
	{
		return array(
			UserEvents::NEW_USER_CREATED => 'onNewUser'
		);
	}

	public function onNewUser(UserEvent $event)
	{
		$this->sendConfirmationEmail($event->getUser());
	}

    private function sendConfirmationEmail(User $user)
    {
		$message = \Swift_Message::newInstance()
		->setSubject('Confirm account')
		->setFrom('noreply@matthias.com')
		->setTo($user->getEmail())
		->setBody('Welcome! ...');

		$this->mailer->send($message);
    }
}