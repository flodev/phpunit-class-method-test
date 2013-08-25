<?php

namespace Tests;

require_once __DIR__ . '/../PHPUnit/TestClass.php';

use PHPUnit\Framework\ClassMethodTest\Build as BuildClassMethodTest;

class BuildTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateClass()
    {
        $proxy = BuildClassMethodTest::from('\PHPUnit\TestClass')
                ->testMethod('privateFuncTest')
                ->copyAllProperties()
                ->mockClassProperty('test', $this->getMock('TestClass'))
                ->create();

        $this->assertInstanceOf('\PHPUnit\Framework\ClassMethodTest\ClassProxy', $proxy);
        $this->assertEquals('Im private', $proxy->createInstance()->privateFuncTest());
    }
}