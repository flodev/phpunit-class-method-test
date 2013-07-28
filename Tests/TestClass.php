<?php

namespace Tests;

/**
 * @author florianbiewald@gmail.com
 */
class TestClass
{
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