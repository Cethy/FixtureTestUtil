<?php
namespace TestUtil\Helper;

/**
 * Shortcut returning mocked entities with id usable
 */
class EntityMockHelper
{
    /**
     * Return mocked entities to enable the usage of entity->getId()
     *
     * @param string  $entityClass
     * @param integer $id
     * @param array   $constructArgs
     *
     * @return mixed|\PHPUnit_Framework_MockObject_MockObject
     */
    static public function getEntityMock(\PHPUnit_Framework_TestCase $testCase, $entityClass, $id, array $constructArgs = null)
    {
        $entityMockBuilder = $testCase
            ->getMockBuilder($entityClass)
            ->setMethods(['getId'])
        ;

        if(null === $constructArgs) {
            $entityMock = $entityMockBuilder
                ->disableOriginalConstructor()
                ->getMock()
            ;
        }
        else {
            $entityMock = $entityMockBuilder
                ->enableOriginalConstructor()
                ->setConstructorArgs($constructArgs)
                ->getMock()
            ;
        }


        $entityMock
            ->expects($testCase->any())
            ->method('getId')
            ->will($testCase->returnValue($id))
        ;

        return $entityMock;
    }
}
