<?php

namespace AppBundle\EventListener;

use AppBundle\Event\UserEvents;
use AppBundle\Event\UserEvent;
use AppBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SendConfirmationMailListener implements EventSubscriberInterface
{
    /** @var \Swift_Mailer */
    private $mailer;

    /** @var Router */
    private $router;

    /** @var \Twig_Environment */
    private $twig;

    /**
     * @param \Swift_Mailer $mailer
     * @param Router        $router
     */
    public function __construct(\Swift_Mailer $mailer, Router $router, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
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
        $mailBody = $this->renderConfirmationMailBody($user);

        $message = \Swift_Message::newInstance()
        ->setSubject('Confirm account')
        ->setFrom('info@matkodjipalo.com')
        ->setTo($user->getEmail())
        ->setBody($mailBody);

        $this->mailer->send($message);
    }

    /**
     * @param  User $user
     * @return string
     */
    private function renderConfirmationMailBody(User $user)
    {
        return $this->twig->render(
            'email/registration.html.twig',
            array(
                'user' => $user,
                'confirmationUrl' => $this->getConfirmationUrl($user)
            )
        );
    }

    /**
     * @param  User user
     * @return string
     */
    private function getConfirmationUrl(User $user)
    {
        return $this->router
                    ->generate(
                        'user_registration_confirmation',
                        [
                            'confirmationCode' => $user->getConfirmationCode(),
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );
    }
}
