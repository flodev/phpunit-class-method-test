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


    public function __construct(\ReflectionClass $class, Build $build)
    {
        $this->class = $class;
        $this->build = $build;
    }

    /**
     *
     * @return mixed
     */
    public function createInstance()
    {
        if ($this->build->hasClassPropertyMocks()) {
            return $this->class->newInstanceArgs(array_values($this->build->get('propertyMocks')));
        } else {
            return $this->class->newInstance();
        }
    }
}
