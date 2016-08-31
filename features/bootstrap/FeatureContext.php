<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
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
     * @Given there is site user :username with password :password
     */
    public function thereIsSiteUserWithPassword($username, $password)
    {
        $user = new \AppBundle\Entity\User();
        $user->setPlainPassword($password);
        $user->setEmail($username);
        $user->setFirstName('Adam');
        $user->setLastName('Adminić');
        $user->setRegistrationDt(new \DateTime());
        $user->setLastLoginDt(new \DateTime());
        $user->setRoles(array('ROLE_USER'));
        $user->setIsActive(true);

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
        for ($i = 0; $i < $count; ++$i) {
            $toDoList = new \AppBundle\Entity\ToDoList();
            $toDoList->setName('ToDo List_'.$i);
            $toDoList->setAuthor($this->currentUser);

            $this->getEntityManager()->persist($toDoList);
        }

        $this->getEntityManager()->flush();
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
        $this->currentUser = $this->thereIsSiteUserWithPassword('user@user.com', 'user');

        $this->visitPath('/login');

        $this->getPage()->fillField('Username', 'user@user.com');
        $this->getPage()->fillField('Password', 'user');
        $this->getPage()->pressButton('Login');
    }

    /**
     * Pauses the scenario until the user presses a key. Useful when debugging a scenario.
     *
     * @Then (I )break
     */
    public function iPutABreakpoint()
    {
        fwrite(STDOUT, "\033[s    \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");
        while (fgets(STDIN, 1024) == '') {
        }
        fwrite(STDOUT, "\033[u");

        return;
    }

    /**
     * @When I wait for a :time seconds
     */
    public function iWaitForASeconds($time)
    {
        $this->getSession()->wait(
            $time * 1000,
            '(0 === jQuery.active)'
        );
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
