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
     * @param \PHPUnit\Framework\MethodTest\MethodTest $methodTest
     */
    public function __construct(MethodTest $methodTest)
    {
        $this->methodTest = $methodTest;
        $this->templateDir = $templateDir   = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Template' .
                         DIRECTORY_SEPARATOR;
    }

    public function generateClass()
    {
        $classTemplate = new \Text_Template(
            $this->templateDir . 'class.tpl'
        );

        $classTemplate->setVar(array(
            'prologue' => 'class',
            'class_declaration' => $this->methodTest->get('methodTestClassName'),
            'methods' => $this->getMethods()
        ));

        $template = $classTemplate->render();

        print_r($template);
    }

    private function getMethods()
    {
        $classTemplate = new \Text_Template(
            $this->templateDir . 'method.tpl'
        );

        $methods = array();

        foreach ($this->methodTest->get('methods') as $method) {
            $classTemplate->setVar(array(
                'modifier' => 'public',
                'definition' => $method,
                'body' => ''
            ));
            $methods[] = $classTemplate->render();
        }

        return implode('', $methods);
    }
}