<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ToDoListRepository extends EntityRepository
{
    /**
     * @return Product[]
     */
    public function findAllPublished()
    {
        return $this->findBy(array(
            'isPublished' => true
        ));
    }

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

    /**
     * @param string $term
     * @return Product[]
     */
    public function search($term)
    {
        if (!$term) {
            return $this->findAll();
        }

        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->getQuery()
            ->execute();
    }
}
