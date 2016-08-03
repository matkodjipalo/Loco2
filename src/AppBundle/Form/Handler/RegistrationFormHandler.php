<?php

namespace AppBundle\Form\Handler;

use AppBundle\DomainManager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

class RegistrationFormHandler
{
    /** @var UserManager */
    private $userManager;

    /**
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @param  FormInterface $form
     * @param  Request       $request
     * @return bool
     */
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
