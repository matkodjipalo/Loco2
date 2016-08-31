<?php

namespace AppBundle\Doctrine;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Rebuilda tablice u bazi.
 */
class SchemaManager
{
    /** @var EntityManager */
    private $em;

    /** @var ContainerInterface */
    private $container;

    /**
     * @param EntityManager      $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function loadFixtures()
    {
        $loader = new ContainerAwareLoader($this->container);
        $loader->loadFromDirectory(__DIR__.'/../DataFixtures');
        $purger = new ORMPurger();
        $executor = new ORMExecutor($this->em, $purger);
        $executor->execute($loader->getFixtures());
    }
}
