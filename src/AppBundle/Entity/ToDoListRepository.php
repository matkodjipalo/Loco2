<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ToDoListRepository extends EntityRepository
{
    /**
     * @return ToDoList[]
     */
    public function findAllPublished()
    {
        return $this->findBy(array(
            'isPublished' => true,
        ));
    }

    /**
     * @param User  $author
     * @param mixed $orderBy
     * @param mixed $orderDirection
     *
     * @return ToDoList[]
     */
    public function findByAuthor(User $author, $orderBy = false, $orderDirection = false)
    {
        if (!$orderBy) {
            return $this->findBy(['author' => $author->getEmail()]);
        }

        $orderDirection = isset($orderDirection) ? $orderDirection : 'DESC';

        return $this->findBy(
            ['author' => $author->getEmail()],
            [$orderBy => $orderDirection]
        );
    }
}
