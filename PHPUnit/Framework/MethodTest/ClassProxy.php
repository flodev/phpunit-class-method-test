<?php
/**
 * @author Florian Biewald
 */

namespace PHPUnit\Framework\MethodTest;

class ClassProxy
{
    /**
     *
     * @var \ReflectionClass
     */
    private $class = null;

    /**
     *
     * @var MethodTest
     */
    private $methodTest = null;

    public function __construct(\ReflectionClass $class, MethodTest $methodTest)
    {
        $this->class = $class;
        $this->methodTest = $methodTest;
    }

    public function createInstance()
    {
        return $this->class->newInstanceArgs(array_values($this->methodTest->get('propertyMocks')));
    }
}
