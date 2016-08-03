<?php

namespace AppBundle\Form\Handler;

use AppBundle\DomainManager\ToDoListManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use AppBundle\Entity\User;

class ToDoListFormHandler
{
    /** @var ToDoListManager */
    private $toDoListManager;

    /**
     * @param ToDoListManager $toDoListManager
     */
    public function __construct(ToDoListManager $toDoListManager)
    {
        $this->toDoListManager = $toDoListManager;
    }

    /**
     * @param  FormInterface $form
     * @param  Request       $request
     * @param  User          $user
     * @return boolean
     */
    public function handle(FormInterface $form, Request $request, User $user)
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }

        $form->getData()->setAuthor($user);

        $this->toDoListManager->createToDoList($form->getData());

        return true;
    }
}
