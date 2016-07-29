<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $formHandler = $this->get('registration_form_handler');

        if ($formHandler->handle($form, $request)) {
			$this->sendConfirmationEmail($user);

			return $this->redirectToRoute('homepage');
		}

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }

    private function sendConfirmationEmail(User $user)
    {
		$message = \Swift_Message::newInstance()
		->setSubject('Confirm account')
		->setFrom('noreply@matthias.com')
		->setTo($user->getEmail())
		->setBody('Welcome! ...');

		$this->get('mailer')->send($message);
    }
}