<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace Tests;

use ClassMethodTest\ClassParser;

require_once __DIR__ . '/../TestObjects/TestClass.php';

class ClassParserTest extends \PHPUnit_Framework_TestCase
{
    public function testFunctionExtract()
    {
        $parser = new ClassParser('\TestObjects\TestClass');
        $this->assertContains('public', $parser->extractFunction('privateFuncTest'));
        $this->assertContains('public', $parser->extractFunction('privateStaticFuncTest'));
        $this->assertContains('public', $parser->extractFunction('protectedStaticFuncTest'));
        $this->assertContains('public', $parser->extractFunction('protectedStaticFinalFuncTest'));
        $this->assertEquals('namespace TestObjects;', $parser->getNamespace());
    }

    public function testPropertyExtract()
    {
        $parser = new ClassParser('\TestObjects\TestClass');
        $parser->extractProperties();
    }

    public function testConstantsExtract()
    {
        $parser = new ClassParser('\TestObjects\TestClass');
        $constants = $parser->extractConstants();
    }

    public function testGetOrderedMethods()
    {
        $parser = new ClassParser('\TestObjects\TestClass');
        $orderedMethods = $parser->getOrderedMethods(array('protectedStaticFuncTest', 'privateFuncTest'));
        $this->assertEquals($orderedMethods[0], 'privateFuncTest');
        $this->assertEquals($orderedMethods[1], 'protectedStaticFuncTest');
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
