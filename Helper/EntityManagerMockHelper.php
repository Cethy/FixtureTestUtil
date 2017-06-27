<?php
namespace Cethyworks\FixtureTestUtil\Helper;

use Doctrine\ORM\EntityManager;

/**
 * Shortcut to create EntityManager Mock object
 */
class EntityManagerMockHelper
{
    /**
     * @return EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    static public function getEntityManagerMock(\PHPUnit_Framework_TestCase $testCase)
    {
        $emMock = $testCase
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $emMock;
    }

    /**
     * @return EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    static public function getEntityManagerMockExpectingOncePersistAndFlush(\PHPUnit_Framework_TestCase $testCase, $expectedEntityToPersist)
    {
        $emMock = static::getEntityManagerMock($testCase);
        $emMock->expects($testCase->once())
            ->method('persist')
            ->with($testCase->equalTo($expectedEntityToPersist));
        ;
        $emMock->expects($testCase->once())
            ->method('flush')
        ;

        return $emMock;
    }
}
