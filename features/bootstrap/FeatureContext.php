<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

require_once __DIR__.'/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context, SnippetAcceptingContext
{
    use \Behat\Symfony2Extension\Context\KernelDictionary;

    private $currentUser;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->getContainer()->get('doctrine')->getManager());
        $purger->purge();
    }

    /**
     * @Given there is an user :useremail with password :password
     */
    public function thereIsAUserWithPassword($useremail, $password)
    {
        $user = new \AppBundle\Entity\User();
        $user->setPlainPassword('user');
        $user->setEmail('user@user.com');
        $user->setFirstName('Adam');
        $user->setLastName('Adminić');
        $user->setRegistrationDt(new \DateTime());
        $user->setLastLoginDt(new \DateTime());
        $user->setRoles(array('ROLE_USER'));

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }

    /**
     * @Given there are :count todo lists
     */
    public function thereAreTodoLists($count)
    {
        $em = $this->getEntityManager();
        for ($i=0; $i < $count; $i++) {
            $toDoList = new \AppBundle\Entity\ToDoList();
            $toDoList->setName('ToDo List_'.$i);
            $toDoList->setAuthor($this->currentUser);

            $em->persist($toDoList);
        }

        $em->flush();
    }

    /**
     * @Then I should see :count todo lists
     */
    public function iShouldSeeTodoLists($count)
    {
        $table = $this->getPage()->find('css', 'table.table');
        assertNotNull($table, 'Cannot find a table!');
        assertCount(intval($count), $table->findAll('css', 'tbody tr'));
    }

    /**
     * @When I click :linkName
     */
    public function iClick($linkName)
    {
        $this->getPage()->clickLink($linkName);
    }

    /**
     * @Given I am logged in as an user
     */
    public function iAmLoggedInAsAnUser()
    {
        $this->currentUser = $this->thereIsAUserWithPassword('user@user.com', 'user');

        $this->visitPath('/login');

        $this->getPage()->fillField('Username', 'user@user.com');
        $this->getPage()->fillField('Password', 'user');
        $this->getPage()->pressButton('Login');
    }


    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    private function getPage()
    {
        return $this->getSession()->getPage();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
