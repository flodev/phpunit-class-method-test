<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace Tests;

use PHPUnit\Framework\ClassMethodTest\ClassProxy;

require_once __DIR__ . '/../TestObjects/TestClass.php';

class ClassProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $proxy = new ClassProxy(
            new \ReflectionClass('\TestObjects\TestClass'),
            $this->getBuildMock()
        );
        $this->assertEquals(
            '12',
            $proxy->createInstance()->exec('classProxyTest', '1', '2')
        );
    }

    public function testWithPropertyMocks()
    {
        $proxy = new ClassProxy(
            new \ReflectionClass('\TestObjects\TestClass'),
            $this->getBuildMock(true)
        );
        $this->assertEquals(
            '12',
            $proxy->createInstance()->exec('classProxyTest', '1', '2')
        );
    }

    private function getBuildMock($includePropertyMocks = false)
    {
        $mock = $this->getMockBuilder('PHPUnit\Framework\ClassMethodTest\Build')
                ->disableOriginalConstructor()
                ->getMock();

        if ($includePropertyMocks) {
            $mock->expects($this->any())->method('hasClassPropertyMocks')
                ->will($this->returnValue(true));
        }

        $mock->expects($this->any())->method('get')
                ->will($this->returnValue(array()));

        return $mock;
    }
}
