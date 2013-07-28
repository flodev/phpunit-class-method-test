<?php

namespace PHPUnit\Framework\MethodTest;

class ClassGenerator
{
    /**
     *
     * @var MethodTest
     */
    private $methodTest = null;

    /**
     *
     * @var string
     */
    private $templateDir = null;

    /**
     *
     * @var ClassParser
     */
    private $parser = null;

    /**
     *
     * @param \PHPUnit\Framework\MethodTest\MethodTest $methodTest
     * @param \PHPUnit\Framework\MethodTest\ClassParser $parser
     */
    public function __construct(MethodTest $methodTest, ClassParser $parser)
    {
        $this->methodTest = $methodTest;
        $this->templateDir = $templateDir   = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Template' .
                         DIRECTORY_SEPARATOR;
        $this->parser = $parser;
    }

    /**
     *
     * @return \PHPUnit\Framework\MethodTest\ClassProxy
     * @throws \PHPUnit_Framework_Exception
     */
    public function generateClass()
    {
        $classTemplate = new \Text_Template(
            $this->templateDir . 'class.tpl'
        );

        $classTemplate->setVar(array(
            'prologue' => 'class',
            'class_declaration' => $this->methodTest->get('methodTestClassName'),
            'vars' => $this->getVars(),
            'methods' => $this->getMethods(),
            'namespace' => $this->parser->getNamespace()
        ));

        $template = $classTemplate->render();

        $this->evalClass($template);

        try {
            $reflClass = new \ReflectionClass($this->getTestClassName());
        } catch (\Exception $e) {
            throw new \PHPUnit_Framework_Exception('Cannot instantiate ClassMethod TestClass', null, $e);
        }

        return new ClassProxy($reflClass, $this->methodTest);
    }

    /**
     *
     * @return string
     */
    private function getTestClassName()
    {
        $ns = $this->parser->getReflection()->getNamespaceName();
        return'\\' . $ns . '\\' . $this->methodTest->get('methodTestClassName');
    }

    private function evalClass($code)
    {
        if (!class_exists($this->getTestClassName())) {
            eval($code);
        }
    }

    /**
     *
     * @return string
     */
    private function getVars()
    {
        if ($this->methodTest->get('copyAllVars')) {
            $props = $this->parser->extractProperties();

            if (!count($props)) {
                return '';
            }
            return implode("\n", $props);
        }
    }

    /**
     *
     * @return string
     */
    private function getMethods()
    {
        $methods = array();

        if ($this->isConstructorNeeded()) {
            $methods[] = $this->getConstructor();
        }

        foreach ($this->methodTest->get('methods') as $method) {
            $methods[] = $this->parser->extractFunction($method);
        }

        return implode('', $methods);
    }

    /**
     *
     * @return bool
     */
    private function isConstructorNeeded()
    {
        return count($this->methodTest->get('propertyMocks')) > 0;
    }

    /**
     *
     * @return string
     */
    private function getConstructor()
    {
        $vars = array();
        $argumentsToInstanceVars = array();

        foreach ($this->methodTest->get('propertyMocks') as $name => $mock) {
            $vars[] = $name;
            $argumentsToInstanceVars[] = $this->getIndent(2) . '$this->' . $name . ' = ' . '$' . $name . ';';
        }

        # open constructor
        $constructor = "\n" . $this->getIndent(1) . 'public function __construct(';
        # function arguments
        $constructor.= '$' . implode(', $', $vars) . "){\n";
        # set arguments to instance variables
        $constructor.= implode("\n", $argumentsToInstanceVars) . "\n";
        # close constructor
        $constructor.= $this->getIndent(1) . "}\n\n";

        return $constructor;
    }

    /**
     *
     * @param int $level
     * @return string
     */
    private function getIndent($level = 0)
    {
        $indentSpaces = 4;
        return str_pad('', $indentSpaces * $level);
    }
}