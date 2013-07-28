<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace PHPUnit\Framework\ClassMethodTest;

use PHPUnit\Framework\ClassMethodTest\ClassGenerator;

class Build
{
    private $classPrefix = 'ClassMethodTest_';

    private $className = null;

    private $methodTestClassName = null;

    private $propertyMocks = array();

    private $methods = array();

    private $copyAllProperties = false;

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
     * @return \PHPUnit\Framework\ClassMethodTest\Build
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
     * @return \PHPUnit\Framework\ClassMethodTest\Build
     */
    public function testMethod($method)
    {
        $this->methods[] = $method;
        return $this;
    }

    /**
     *
     * @return \PHPUnit\Framework\ClassMethodTest\Build
     */
    public function copyAllProperties()
    {
        $this->copyAllProperties = true;
        return $this;
    }

    /**
     *
     * @param string $varName
     * @param object $mock
     * @return \PHPUnit\Framework\ClassMethodTest\Build
     */
    public function mockClassProperty($varName, $mock)
    {
        $this->propertyMocks[$varName] = $mock;
        return $this;
    }

    /**
     *
     * @param string $property
     * @return mixed
     * @throws \PHPUnit_Framework_Error
     */
    public function get($property)
    {
        if (!$this->{$property}) {
            throw new \PHPUnit_Framework_Error("Property $property does not exists.");
        }
        return $this->{$property};
    }
}
