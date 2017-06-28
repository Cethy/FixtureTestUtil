<?php

namespace Cethyworks\FixtureTestUtil\Repository;

use Cethyworks\FixtureTestUtil\AbstractFixtureAwareTestCase;
use Doctrine\ORM\EntityRepository;

/**
 * Useful to test Repositories
 *
 * Provides shortcut to get the EntityRepository.
 * Extends AbstractFixtureAwareTestCase for fixture loading & awareness.
 */
abstract class AbstractRepositoryTestCase extends AbstractFixtureAwareTestCase
{
    /**
     * Must return the entity name handled by the repository tested
     *
     * @return string
     */
    abstract protected function getEntityName();

    /**
     * @return EntityRepository
     */
    protected function getEntityRepository()
    {
        return $this->getEntityManager()->getRepository($this->getEntityName());
    }
}
