<?php

namespace Cethyworks\FixtureTestUtil\Fixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Helper to create entity fixtures
 */
abstract class AbstractLoadData extends AbstractFixture
{
    /**
     * Return data used to create entity fixtures
     * eg: return [
     *   'reference'   => ['name' => 'foo'],
     *   'reference-2' => ['name' => 'bar'],
     * ];
     *
     * @return array
     */
    abstract protected function getData();

    /**
     * Method responsible of creating each entity fixtures
     * eg: {
     *   $foo = new Foo();
     *   $foo->setName('bar');
     *   return $foo;
     * }
     *
     * @todo transform into an overridable generalized method ? eg: $object->{'set'.$key}($value)
     *
     * @param array $data
     * @return Object
     */
    abstract protected function createEntity(array $data);

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach($this->getData() as $reference => $data) {
            $entity = $this->createEntity($data);

            $manager->persist($entity);
            $this->addReference($reference, $entity);
        }

        $manager->flush();
    }
}
