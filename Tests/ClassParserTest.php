<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace Tests;

use PHPUnit\Framework\ClassMethodTest\ClassParser;

class ClassParserTest extends \PHPUnit_Framework_TestCase
{
    public function testFunctionExtract()
    {
        $parser = new ClassParser('Tests\TestClass');
        $this->assertContains('public', $parser->extractFunction('privateFuncTest'));
        $this->assertContains('public', $parser->extractFunction('privateStaticFuncTest'));
        $this->assertContains('public', $parser->extractFunction('protectedStaticFuncTest'));
        $this->assertContains('public', $parser->extractFunction('protectedStaticFinalFuncTest'));
        $this->assertEquals('namespace Tests;', $parser->getNamespace());
    }

    public function testPropertyExtract()
    {
        $parser = new ClassParser('Tests\TestClass');
        $parser->extractProperties();
    }

    public function testConstantsExtract()
    {
        $parser = new ClassParser('Tests\TestClass');
        $constants = $parser->extractConstants();
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
