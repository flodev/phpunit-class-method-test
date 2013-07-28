<?php

namespace Tests;

use PHPUnit\Framework\MethodTest\MethodTest;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateClass()
    {
        MethodTest::from('TestClass')
                ->testMethod('testMethod')
                ->copyAllVars()
                ->mockClassVar('test', $this->getMock('TestClass'))
                ->create();
    }
}