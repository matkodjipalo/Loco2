<?php

namespace AppBundle\Doctrine;

use AppBundle\DataFixtures\ORM\LoadFixtures;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Rebuilda tablice u bazi
 */
class SchemaManager
{
    /** @var EntityManager */
    private $em;

    /** @var EntityManager */
    private $container;

    /**
     * @param EntityManager $em
     * @param EntityManager $container
     */
    public function __construct(EntityManager $em, EntityManager $container)
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
