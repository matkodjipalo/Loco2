<?php

namespace AppBundle\DomainManager;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Entity\ToDoList;
use AppBundle\Entity\ToTask;

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
        $this->entityManager->persist($toDoList);
        $this->connectTasksWithToDoList($toDoList);
        $this->entityManager->flush();
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
}
