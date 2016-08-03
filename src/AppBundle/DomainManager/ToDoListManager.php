<?php

namespace AppBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Entity\ToDoList;
use AppBundle\Entity\ToTask;
use Doctrine\Common\Collections\ArrayCollection;

class ToDoListManager
{
    /** @param EntityManager */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ToDoList $toDoList
     */
    public function createToDoList(ToDoList $toDoList)
    {
        $this->doPersist($toDoList);
    }

    /**
     * @param  ToDoList        $modifiedToDoList
     * @param  ArrayCollection $originalTasks
     */
    public function updateToDoList(ToDoList $modifiedToDoList, ArrayCollection $originalTasks)
    {
        // uklanja vezu todo liste i taska ako je bilo nekakve promjene u tasku
        foreach ($originalTasks as $task) {
            if (false === $modifiedToDoList->getTasks()->contains($task)) {
                $task->setToDoList(null);
                $this->entityManager->remove($task);
            }
        }

        $this->doPersist($modifiedToDoList);
    }

    /**
     * @param ToDoList $toDoList
     */
    private function connectTasksWithToDoList(ToDoList $toDoList)
    {
        foreach ($toDoList->getTasks() as $task) {
            $task->setToDoList($toDoList);
            $this->entityManager->persist($task);
        }
    }

    /**
     * @param  ToDoList $toDoList
     */
    private function doPersist(ToDoList $toDoList)
    {
        $this->entityManager->persist($toDoList);
        $this->connectTasksWithToDoList($toDoList);
        $this->entityManager->flush();
    }
}
