<?php

namespace AppBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\User;
use AppBundle\DomainManager\ToDoListManager;

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
    public function handleCreate(FormInterface $form, Request $request, User $user)
    {
        if (false === $this->handle($form, $request)) {
            return false;
        }

        $form->getData()->setAuthor($user);

        $this->toDoListManager->createToDoList($form->getData());

        return true;
    }

    /**
     * @param  FormInterface $form
     * @param  Request       $request
     * @param  User          $user
     * @return boolean
     */
    public function handleEdit(FormInterface $form, Request $request, $originalTasks)
    {
        if (false === $this->handle($form, $request)) {
            return false;
        }

        $this->toDoListManager->updateToDoList($form->getData(), $originalToDoList);

        return true;
    }

    private function handle(FormInterface $form, Request $request)
    {
        if (!$request->isMethod('POST')) {
            return false;
        }

        $form->handleRequest($request);

        if (!$form->isValid()) {
            return false;
        }
    }
}
