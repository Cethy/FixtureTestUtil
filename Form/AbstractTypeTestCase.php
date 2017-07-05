<?php

namespace Cethyworks\FixtureTestUtil\Form;

use Cethyworks\FixtureTestUtil\AbstractFixtureAwareTestCase;
use Cethyworks\FixtureTestUtil\AbstractRepositoryTestCase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormExtensionInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\PreloadedExtension;

/**
 * Add Fixture awareness to TypeTestCase (based on WebTestCase)
 */
abstract class AbstractTypeTestCase extends AbstractFixtureAwareTestCase
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var FormBuilder
     */
    protected $builder;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    public function setUp()
    {
        parent::setUp();
        $this->dispatcher = $this->getMockBuilder('Symfony\Component\EventDispatcher\EventDispatcherInterface')->getMock();

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->getFormFactory()
        ;
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }

    /**
     * Must return all the FormTypes needing the EntityManager in their constructor
     * They will be passed to the PreloadExtension
     *
     * @see AbstractFixtureAwareTypeTestCase::getExtensions()
     *
     * @return AbstractType[]
     */
    abstract protected function getEntityManagerAwareTypeClasses();

    /**
     * @return FormExtensionInterface[]
     */
    protected function getExtensions()
    {
        $entityManager = $this->getEntityManager();
        $preloadedTypes = [
            new EntityType(self::$kernel->getContainer()->get('doctrine')),
        ];
        
        foreach($this->getEntityManagerAwareTypeClasses() as $type) {
            $preloadedTypes[] = new $type($entityManager);
        }

        return [
            // register the type instances with the PreloadedExtension
            new PreloadedExtension($preloadedTypes, []),
        ];
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    abstract protected function getForm();
}
