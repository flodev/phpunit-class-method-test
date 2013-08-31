<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace ClassMethodTest;

use ClassMethodTest\ParseInfo;

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

    /**
     *
     * @var Build
     */
    private $instance = null;

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
            $this->instance = $this->class->newInstanceArgs(array_values($this->build->get('propertyMocks')));
        } else {
            $this->instance = $this->class->newInstance();
        }
        return $this;
    }

    /**
     *
     * @param string $methodname
     * @param mixed $arguments
     * @return mixed
     */
    public function exec($methodname)
    {
        $args = func_get_args();
        array_shift($args);
        ParseInfo::getInstance()->addCalledMethod($methodname);
        return call_user_func_array(array($this->instance, $methodname), $args);
    }
}
