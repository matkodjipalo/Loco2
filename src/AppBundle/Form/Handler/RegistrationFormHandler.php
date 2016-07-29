<?php

namespace AppBundle\Form\Handler;

use AppBundle\DomainManager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class RegistrationFormHandler
{
	private $userManager;

	public function __construct(UserManager $userManager)
	{
		$this->userManager = $userManager;
	}

	public function handle(FormInterface $form, Request $request)
	{
		if (!$request->isMethod('POST')) {
			return false;
		}

		$form->handleRequest($request);

		if (!$form->isValid()) {
			return false;
		}

		$this->userManager->createUser($form->getData());

		return true;
	}

}