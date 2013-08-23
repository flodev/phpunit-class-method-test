<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace Tests;

use PHPUnit\Framework\ClassMethodTest\ParseInfo;

class ParseInfoTest extends \PHPUnit_Framework_TestCase
{
    public function testGetXDebugInfo()
    {
//        $this->assertGreaterThan(0, ParseInfo::getClassGeneratorXDebugIndex());

        ParseInfo::getClassGeneratorXDebugIndex();
//        exit;
    }
}
