<?php

namespace Cethyworks\FixtureTestUtil\Helper;

/**
 * Shortcut to create Repository Mock object
 */
class RepositoryMockHelper
{
    /**
     * @return mixed|\PHPUnit_Framework_MockObject_MockObject
     */
    static public function getRepositoryMock(\PHPUnit_Framework_TestCase $testCase, $repositoryClass, array $mockedMethods = [])
    {
        $repositoryMock = $testCase
            ->getMockBuilder($repositoryClass)
            ->disableOriginalConstructor()
            ->setMethods($mockedMethods)
            ->getMock()
        ;

        return $repositoryMock;
    }
}
