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

        print_r($template);
        exit;
    }

    private function getVars()
    {
        if ($this->methodTest->get('copyAllVars')) {
            
        }
    }

    private function getMethods()
    {
        $methods = array();

        foreach ($this->methodTest->get('methods') as $method) {
            $methods[] = $this->parser->extractFunction($method);
        }

        return implode('', $methods);
    }
}