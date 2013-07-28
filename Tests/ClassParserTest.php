<?php
/**
 * @author florianbiewald@gmail.com
 */

namespace Tests;

use PHPUnit\Framework\MethodTest\ClassParser;

class ClassParserTest extends \PHPUnit_Framework_TestCase
{
    public function testClassParserFunctionExtract()
    {
        $parser = new ClassParser('Tests\TestClass');
        $this->assertContains('public', $parser->extractFunction('privateFuncTest'));
        $this->assertContains('public', $parser->extractFunction('privateStaticFuncTest'));
        $this->assertContains('public', $parser->extractFunction('protectedStaticFuncTest'));
        $this->assertContains('public', $parser->extractFunction('protectedStaticFinalFuncTest'));
    }

    /**
     * @expectedException PHPUnit_Framework_Exception
     * @expectedExceptionMessage Class has no file: ClassParserTestTestClass
     */
    public function testThrowWhenClassHasNoFile()
    {
        $class = 'class ClassParserTestTestClass{
            private function test(){
            }
        }';
        eval($class);

        $parser = new ClassParser('\ClassParserTestTestClass');
        $this->assertContains('public', $parser->extractFunction('test'));
    }
}
