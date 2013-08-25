<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace PHPUnit\Framework\ClassMethodTest;

use PHPUnit\Framework\ClassMethodTest\ClassGenerator;

class Build
{
    /**
     *
     * @var string
     */
    private $className = null;

    /**
     *
     * @var array
     */
    private $propertyMocks = array();

    /**
     *
     * @var array
     */
    private $methods = array();

    /**
     *
     * @var boolean
     */
    private $copyAllProperties = false;

    /**
     *
     * @param string $className
     */
    private function __construct($className)
    {
        $this->className = $className;
    }

    private function __clone()
    {
    }

    /**
     *
     * @param string $className
     * @return \PHPUnit\Framework\ClassMethodTest\Build
     */
    public static function from($className)
    {
        ParseInfo::reset();
        return new self($className);
    }

    /**
     *
     * @return \PHPUnit\Framework\ClassMethodTest\ClassProxy
     */
    public function create()
    {
        ParseInfo::getInstance()->setTestMethods($this->methods);
        $generator = new ClassGenerator($this, new ClassParser($this->className));
        return $generator->generateClass();
    }

    /**
     *
     * @param string $method
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
     * @return bool
     */
    public function hasClassPropertyMocks()
    {
        return count($this->propertyMocks) > 0;
    }

    /**
     *
     * @param string $property
     * @return mixed
     * @throws \PHPUnit_Framework_Exception
     */
    public function get($property)
    {
        if (!property_exists($this, $property)) {
            throw new \PHPUnit_Framework_Exception("Property $property does not exists.");
        }
        return $this->{$property};
    }
}
