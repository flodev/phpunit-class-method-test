<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace Tests;

use ClassMethodTest\ParseInfo;

class ParseInfoTest extends \PHPUnit_Framework_TestCase
{
    public function testSetterAndGetter()
    {
        $parseInfo = ParseInfo::getInstance();
        $parseInfo->setGeneratedClassFilePath("test");
        $this->assertEquals("test", $parseInfo->getGeneratedClassFilePath());
        $parseInfo->setGeneratedClassName("test1");
        $this->assertEquals("test1", $parseInfo->getGeneratedClassName());
        $parseInfo->setTestClassFilePath("test2");
        $this->assertEquals("test2", $parseInfo->getTestClassFilePath());
        $parseInfo->setTestClassName("test3");
        $this->assertEquals("test3", $parseInfo->getTestClassName());
        $parseInfo->setTestMethods(array("test"));
        $this->assertEquals(array("test"), $parseInfo->getTestMethods());
    }

    public function testReset()
    {
        ParseInfo::getInstance()->setGeneratedClassFilePath("test");
        $this->assertEquals("test", ParseInfo::getInstance()->getGeneratedClassFilePath());
        $instance = ParseInfo::getInstance();
        ParseInfo::reset();
        $this->assertEquals(null, ParseInfo::getInstance()->getGeneratedClassFilePath());
        $this->assertNotEquals($instance, ParseInfo::getInstance());
    }
}
