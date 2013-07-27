<?php
/**
 * @author florianbiewald@gmail.com
 */

namespace PHPUnit\Framework\MethodTest;

use PHPUnit\Framework\MethodTest\ClassGenerator;

class MethodTest
{
    private $className = null;

    private $vars = array();

    private $methods = array();

    private $copyAllVars = false;

    /**
     *
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
        // $this->generator = new
    }

    /**
     *
     * @param string $className
     * @return \self
     */
    public static function from($className)
    {
        return new self($className);
    }

    public function create()
    {

    }

    public function copyMethods(array $methods)
    {
        if ($this->methods) {
            $this->methods = array_merge($this->methods, $methods);
        } else {
            $this->methods = $methods;
        }
        return $this;
    }

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

    public function copyVar($var)
    {
        $this->vars[] = $var;
        return $this;
  }

    public function copyVars(array $vars)
    {
        if ($this->vars) {
            $this->vars = array_merge($this->vars, $vars);
        } else {
            $this->vars = $vars;
        }
        return $this;
    }
}
