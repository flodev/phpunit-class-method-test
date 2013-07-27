<?php

namespace Tests;

use PHPUnit\Framework\MethodTest\MethodTest;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGenerateClass()
    {
        MethodTest::from('TestClass')
                ->copyMethod('importCustomerStreets')
                ->copyMethods(array())
                ->copyAllVars();
    }
}