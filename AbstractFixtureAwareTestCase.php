<?php

namespace Cethyworks\TestUtil;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use \Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Add Fixture awareness & handling to WebTestCase
 */
abstract class AbstractFixtureAwareTestCase extends WebTestCase
{
    /**
     * @var ORMExecutor
     */
    private $fixtureExecutor;

    /**
     * @var ContainerAwareLoader
     */
    private $fixtureLoader;

    /**
     * @var ReferenceRepository
     */
    private $referenceRepository;

    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    protected function getEntityManager()
    {
        if(!$this->entityManager) {
            $this->entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
        }

        return $this->entityManager;
    }

    /**
     * @return ORMExecutor
     */
    private function getFixtureExecutor()
    {
        if (!$this->fixtureExecutor) {
            $entityManager = $this->getEntityManager();
            $purger        = new ORMPurger($entityManager);

            $this->fixtureExecutor = new ORMExecutor($entityManager, $purger);
        }
        return $this->fixtureExecutor;
    }

    /**
     * @return ContainerAwareLoader
     */
    private function getFixtureLoader()
    {
        if (!$this->fixtureLoader) {
            $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
        }
        return $this->fixtureLoader;
    }

    public function setUp()
    {
        self::bootKernel();
    }

    /**
     * Adds a new fixture to be loaded.
     *
     * @param FixtureInterface $fixture
     */
    protected function addFixture(FixtureInterface $fixture)
    {
        $this->getFixtureLoader()->addFixture($fixture);
    }


    /**
     * Executes all the fixtures that have been loaded so far.
     */
    protected function executeFixtures()
    {
        // remove foreign key checks
        $this->getEntityManager()->getConnection()->query(sprintf('SET FOREIGN_KEY_CHECKS=0'));

        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());

        // put back foreign key checks
        $this->getEntityManager()->getConnection()->query(sprintf('SET FOREIGN_KEY_CHECKS=1'));
    }

    /**
     * @param string $name
     * @return object
     */
    protected function getFixtureReference($name)
    {
        if(!$this->referenceRepository) {
            $this->referenceRepository = $this->getFixtureExecutor()->getReferenceRepository();
        }
        return $this->referenceRepository->getReference($name);
    }
}
