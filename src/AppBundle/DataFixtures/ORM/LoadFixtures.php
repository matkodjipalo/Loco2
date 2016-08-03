<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\ToDoList;
use AppBundle\Entity\Task;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Tests\Fixtures\ContainerAwareFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFixtures extends ContainerAwareFixture
{
    /**
     * @param  ObjectManager $manager [description]
     */
    public function load(ObjectManager $manager)
    {
        $defaultAuthor = $this->loadUser($manager);
        $this->loadToDoLists($defaultAuthor, $manager);
    }

    /**
     * @param  EntityManager $em [description]
     * @return User]
     */
    private function loadUser(EntityManager $em)
    {
        $em->createQuery('DELETE FROM AppBundle:Product');

        $user = new User();
        $user->setPlainPassword('user');
        $user->setEmail('user@user.com');
        $user->setFirstName('Adam');
        $user->setLastName('Adminić');
        $user->setRegistrationDt(new \DateTime());
        $user->setLastLoginDt(new \DateTime());
        $user->setRoles(array('ROLE_USER'));

        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @param  User          $defaultAuthor
     * @param  EntityManager $em
     */
    private function loadToDoLists(User $defaultAuthor, EntityManager $em)
    {
        $em->createQuery('DELETE FROM AppBundle:ToDoList');

        $toDoList1 = new ToDoList();
        $toDoList1->setName('Kindle Fire HD 7');
        $toDoList1->setAuthor($defaultAuthor);
        $toDoList1->setCreatedAt(new \DateTime());

        $toDoList2 = new ToDoList();
        $toDoList2->setName('Buđenje ujutro');
        $toDoList2->setAuthor($defaultAuthor);
        $toDoList2->setCreatedAt(new \DateTime());

        

        $em->persist($toDoList1);
        $em->persist($toDoList2);
        $this->loadTasks($toDoList2, $em);
        $em->flush();
    }

    /**
     * @param  ToDoList      $toDoList
     * @param  EntityManager $em
     */
    private function loadTasks(ToDoList $toDoList, EntityManager $em)
    {
        $date = new \DateTime();

        $task = new Task();
        $task->setToDoList($toDoList);
        $task->setDeadlineDt($date->add(new \DateInterval('P10D')));
        $task->setName('Buđenje ujutro u 7');
        $task->setPriority("HIGH");
        $task->setIsCompleted(true);

        $task2 = new Task();
        $task2->setToDoList($toDoList);
        $task2->setDeadlineDt($date->add(new \DateInterval('P2D')));
        $task2->setName('Kopanje vrta');
        $task2->setPriority("HIGH");
        $task2->setIsCompleted(false);

        $em->persist($task);
        $em->persist($task2);
        $em->flush();
    }
}
