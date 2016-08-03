<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AppBundle\Event\UserEvents;
use AppBundle\Event\UserEvent;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendConfirmationMailListener implements EventSubscriberInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var Router */
    private $router;

    /**
     * @param \Swift_Mailer $mailer
     * @param Router        $router
     */
    public function __construct(\Swift_Mailer $mailer, Router $router)
    {
        $this->mailer = $mailer;
        $this->router = $router;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::NEW_USER_CREATED => 'onNewUser'
        );
    }

    /**
     * @param UserEvent $event
     */
    public function onNewUser(UserEvent $event)
    {
        $this->sendConfirmationEmail($event->getUser());
    }

    /**
     * @param User $user
     */
    private function sendConfirmationEmail(User $user)
    {
        $mailBody = $this->createConfirmationMailBody($user);

        $message = \Swift_Message::newInstance()
        ->setSubject('Confirm account')
        ->setFrom('noreply@matthias.com')
        ->setTo($user->getEmail())
        ->setBody($mailBody);

        $this->mailer->send($message);
    }

    /**
     * @param  User   $user
     * @return string
     */
    private function createConfirmationMailBody(User $user)
    {
        $body = 'Hello '.$user->getFirstName().' '.$user->getLastName().'! \r\n\r\n';
        $body .= 'To finish activating your account - please visit ';
        $body .= '<a href="'.urlencode($this->router->generate('user_registration_confirmation', array(
                        'confirmationCode' => $user->getConfirmationCode(),
                         UrlGeneratorInterface::ABSOLUTE_URL
                    ))
                ).'">LINK</a>';
        $body .= ' \r\n\r\nRegards,<br>Locastic.hr';

        return $body;
    }
}
