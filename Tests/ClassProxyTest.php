<?php
/**
 * @author Florian Biewald
 */

namespace Tests;

use PHPUnit\Framework\MethodTest\ClassProxy;

class ClassProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testTest()
    {
        $proxy = new ClassProxy(
            new \ReflectionClass('Tests\TestClass'),
            $this->getMethodTestMock()
        );
        $this->assertInstanceOf('Tests\TestClass', $proxy->createInstance());
    }

    private function getMethodTestMock()
    {
        $mock = $this->getMockBuilder('PHPUnit\Framework\MethodTest\MethodTest')
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->any())->method('get')
                ->will($this->returnValue(array()));

        return $mock;
    }
}
