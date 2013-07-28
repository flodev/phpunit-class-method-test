<?php

namespace Tests;

/**
 * @author Florian Biewald
 */
class TestClass
{
    const TEST = '5';

    private $privatePropString = 'String';

    protected $protectedPropInt = 0;

    public $publicPropNull = null;

    static $staticPropTestConstant = self::TEST;

    private $privatePropArray = array('test' => 'muhu', 'fsfds', 'sdfs');

    private function privateFuncTest()
    {
        return 'Im private';
    }

    static private function privateStaticFuncTest()
    {
        return 'Im private static';
    }

    static protected function protectedStaticFuncTest()
    {
        return 'Im protected static';
    }

    static final protected function protectedStaticFinalFuncTest()
    {
        return 'Im protected static final';
    }

}