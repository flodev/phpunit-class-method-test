<?php
/**
 * @author florianbiewald@gmail.com
 */

namespace PHPUnit\Framework\MethodTest;

use PHPUnit\Framework\MethodTest\ClassGenerator;

class MethodTest
{
    private $classPrefix = 'MethodTest_';

    private $className = null;

    private $methodTestClassName = null;

    private $varMocks = array();

    private $methods = array();

    private $copyAllVars = false;

    /**
     *
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
        $this->generateMethodTestClassName($this->className);
    }

    private function generateMethodTestClassName($className)
    {
        $this->methodTestClassName = $this->classPrefix . $className . substr(md5(microtime()), 0, 8);
    }

    /**
     *
     * @param string $className
     * @return \PHPUnit\Framework\MethodTest\MethodTest
     */
    public static function from($className)
    {
        return new self($className);
    }

    public function create()
    {
        $generator = new ClassGenerator($this, new ClassParser($this->className));
        $generator->generateClass();
    }

    /**
     *
     * @param type $method
     * @return \PHPUnit\Framework\MethodTest\MethodTest
     */
    public function copyMethod($method)
    {
        $this->methods[] = $method;
        return $this;
    }

    public function copyAllVars()
    {
        $this->copyAllVars = true;
        return $this;
    }

    public function mockClassVar($varName, $mock)
    {
        $this->varMocks[$varName] = $mock;
        return $this;
    }

    public function get($property)
    {
        if (!$this->{$property}) {
            throw new \PHPUnit_Framework_Error("Property $property does not exists.");
        }
        return $this->{$property};
    }
}
