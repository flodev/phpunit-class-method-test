<?php

/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace TestObjects;

class TestClass
{
    const TEST = '5';

    const TEST2 = '6';

    private $privatePropString = 'String';

    protected $protectedPropInt = 0;

    public $publicPropNull = null;

    static $staticPropTestConstant = self::TEST;

    private $privatePropArray = array('test' => 'muhu', 'fsfds', 'sdfs');

    private function privateFuncTest()
    {
        $test = "muh";
        if (!$test) {
            echo "lala";
        }
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

    public function classProxyTest($arg1, $arg2)
    {
        return $arg1.$arg2;
    }

}