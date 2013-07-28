<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace Tests;

use PHPUnit\Framework\ClassMethodTest\ClassProxy;

class ClassProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testTest()
    {
        $proxy = new ClassProxy(
            new \ReflectionClass('Tests\TestClass'),
            $this->getBuildMock()
        );
        $this->assertInstanceOf('Tests\TestClass', $proxy->createInstance());
    }

    private function getBuildMock()
    {
        $mock = $this->getMockBuilder('PHPUnit\Framework\ClassMethodTest\Build')
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->any())->method('get')
                ->will($this->returnValue(array()));

        return $mock;
    }
}
