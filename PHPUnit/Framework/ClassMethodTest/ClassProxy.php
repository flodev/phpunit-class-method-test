<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace PHPUnit\Framework\ClassMethodTest;

class ClassProxy
{
    /**
     *
     * @var \ReflectionClass
     */
    private $class = null;

    /**
     *
     * @var Build
     */
    private $build = null;


    public function __construct(\ReflectionClass $class, Build $methodTest)
    {
        $this->class = $class;
        $this->build = $methodTest;
    }

    public function createInstance()
    {
        return $this->class->newInstanceArgs(array_values($this->build->get('propertyMocks')));
    }
}
