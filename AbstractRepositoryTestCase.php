<?php

namespace Cethyworks\TestUtil;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Add EntityRepository awareness to WebTestCase
 *
 * Useful to test Repository queries
 */
abstract class AbstractRepositoryTestCase extends AbstractFixtureAwareTestCase
{
    public function setUp()
    {
        parent::setUp();

        foreach($this->getFixturesInstances() as $fixturesInstance) {
            $this->addFixture($fixturesInstance);
        }

        $this->executeFixtures();
    }


    /**
     * @return string
     */
    abstract protected function getEntityName();

    /**
     * @return FixtureInterface[]
     */
    abstract protected function getFixturesInstances();

    /**
     * @return EntityRepository
     */
    protected function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository($this->getEntityName());
    }
}
