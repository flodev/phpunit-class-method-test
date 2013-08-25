<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 *
 * @todo
 * - consider creating an own class for formatting parser results
 * - test without constructor
 */

namespace PHPUnit\Framework\ClassMethodTest;

use PHPUnit\Framework\ClassMethodTest\ParseInfo;

class ClassGenerator
{
    const CLASS_PREFIX = 'ClassMethodTest';

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
     * @var string
     */
    private $classNameForTest = null;

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
        $this->generateMethodTestClassName();

    }

    private function generateMethodTestClassName()
    {
        $this->classNameForTest = self::CLASS_PREFIX
                . $this->parser->getReflection()->getShortName()
                . substr(md5(microtime()), 0, 8);

        $generatedClassName = '\\';

        if ($this->parser->getNamespaceName() !== null) {
            $generatedClassName.= $this->parser->getNamespaceName() . '\\';
        }
        $generatedClassName.= $this->classNameForTest;
        ParseInfo::getInstance()->setGeneratedClassName($generatedClassName);
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
            'class_declaration' => $this->classNameForTest,
            'vars' => $this->getVars(),
            'methods' => $this->getMethods(),
            'namespace' => $this->parser->getNamespace(),
            'constants' => $this->getConstants()
        ));

        if ($this->isCodeCoverageNeeded()) {
            $this->includeFile($this->createTestClass($classTemplate->render()));
        } else {
            $this->evalClass($classTemplate->render());
        }

        return new ClassProxy($this->getReflectionClass(), $this->build);
    }

    /**
     *
     * @return \ReflectionClass
     * @throws \PHPUnit_Framework_Exception
     */
    private function getReflectionClass()
    {
        try {
            return new \ReflectionClass($this->getTestClassName());
        } catch (\Exception $e) {
            throw new \PHPUnit_Framework_Exception(
                'Cannot instantiate ClassMethod TestClass: ' . $this->getTestClassName(), null, $e
            );
        }
    }

    /**
     *
     * @return boolean
     */
    private function isCodeCoverageNeeded()
    {
        return class_exists('\PHPUnit\Framework\ClassMethodTest\TestListener', false) && xdebug_is_enabled();
    }

    /**
     *
     * @param string $code
     * @return string
     */
    private function createTestClass($code)
    {
        # for some reason i can't use tmpfile() here even though script executing seems not broken
        $file = fopen(tempnam(sys_get_temp_dir(), 'ClassMethodTestClass'), 'w');
        fwrite($file, $code);
        $data = stream_get_meta_data($file);
        if (array_key_exists('uri', $data)) {
            ParseInfo::getInstance()->setGeneratedClassFilePath($data['uri']);
            return $data['uri'];
        }
        return '';
    }

    /**
     *
     * @param string $path
     * @throws \PHPUnit_Framework_Exception
     */
    private function includeFile($path)
    {
        if (empty($path) || !file_exists($path)) {
            throw new \PHPUnit_Framework_Exception('Cannot include file: ' . $path);
        }
        require_once $path;
    }

    /**
     *
     * @return string
     */
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
        return '\\' . $this->parser->getReflection()->getNamespaceName() . '\\' . $this->classNameForTest;
    }

    /**
     *
     * @param string $code
     */
    private function evalClass($code)
    {
        if (!class_exists($this->getTestClassName(), false)) {
            eval($code);
        }
    }

    /**
     *
     * @return string
     */
    private function getVars()
    {
        if ($this->build->get('copyAllProperties')) {
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
        $methods[] = $this->getConstructor();

        foreach ($this->build->get('methods') as $method) {
            $methods[] = $this->parser->extractFunction($method);
        }

        return implode('', $methods);
    }

    /**
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
        if ($vars) {
            $constructor.= '$' . implode(', $', $vars) . "";
        }
        $constructor.= "){\n";
        # set arguments to instance variables
        if ($argumentsToInstanceVars) {
            $constructor.= implode("\n", $argumentsToInstanceVars) . "\n";
        }
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