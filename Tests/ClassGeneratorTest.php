<?php

use PHPUnit\Framework\MethodTest\ClassGenerator;

class ClassGeneratorTest extends PHPUnit_Framework_TestCase
{

    public function testTest()
    {
        $methodTestMock = $this->getMockBuilder('PHPUnit\Framework\MethodTest\MethodTest')
                ->disableOriginalConstructor()->getMock();

        $methodTestMock->expects($this->any())->method('get')->will($this->returnCallback(function($prop) {
            if ($prop === 'methodTestClassName') {
                return 'test';
            }

            if ($prop === 'methods') {
                return array('method1($hallo)');
            }
        }));
        $generator = new ClassGenerator(
                $methodTestMock
        );

        $generator->generateClass();
    }
}