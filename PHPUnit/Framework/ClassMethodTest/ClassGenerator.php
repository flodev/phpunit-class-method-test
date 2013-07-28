<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */

namespace PHPUnit\Framework\ClassMethodTest;

class ClassGenerator
{
    /**
     *
     * @var Build
     */
    private $build = null;

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
     * @param \PHPUnit\Framework\ClassMethodTest\Build $build
     * @param \PHPUnit\Framework\ClassMethodTest\ClassParser $parser
     */
    public function __construct(Build $build, ClassParser $parser)
    {
        $this->build = $build;
        $this->templateDir = $templateDir   = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Template' .
                         DIRECTORY_SEPARATOR;
        $this->parser = $parser;
    }

    /**
     *
     * @return \PHPUnit\Framework\ClassMethodTest\ClassProxy
     * @throws \PHPUnit_Framework_Exception
     */
    public function generateClass()
    {
        $classTemplate = new \Text_Template(
            $this->templateDir . 'class.tpl'
        );

        $classTemplate->setVar(array(
            'prologue' => 'class',
            'class_declaration' => $this->build->get('methodTestClassName'),
            'vars' => $this->getVars(),
            'methods' => $this->getMethods(),
            'namespace' => $this->parser->getNamespace(),
            'constants' => $this->getConstants()
        ));

        $template = $classTemplate->render();

        $this->evalClass($template);

        try {
            $reflClass = new \ReflectionClass($this->getTestClassName());
        } catch (\Exception $e) {
            throw new \PHPUnit_Framework_Exception('Cannot instantiate ClassMethod TestClass', null, $e);
        }

        return new ClassProxy($reflClass, $this->build);
    }

    private function getConstants()
    {
        $constants = $this->parser->extractConstants();
        return count($constants) > 0 ? implode("\n", $constants) : '';
    }

    /**
     *
     * @return string
     */
    private function getTestClassName()
    {
        $ns = $this->parser->getReflection()->getNamespaceName();
        return'\\' . $ns . '\\' . $this->build->get('methodTestClassName');
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
        if ($this->build->get('copyAllVars')) {
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

        foreach ($this->build->get('methods') as $method) {
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
        return count($this->build->get('propertyMocks')) > 0;
    }

    /**
     *
     * @return string
     */
    private function getConstructor()
    {
        $vars = array();
        $argumentsToInstanceVars = array();

        foreach ($this->build->get('propertyMocks') as $name => $mock) {
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