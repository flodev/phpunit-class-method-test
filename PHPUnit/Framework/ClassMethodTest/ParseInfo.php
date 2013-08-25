<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */
namespace PHPUnit\Framework\ClassMethodTest;

class ParseInfo
{
    /**
     *
     * @var ParseInfo
     */
    private static $instance = null;

    /**
     *
     * @var string
     */
    private $generatedClassName = null;

    /**
     *
     * @var string
     */
    private $generatedClassFilePath = null;

    /**
     *
     * @var string
     */
    private $testClassName = null;

    /**
     *
     * @var string
     */
    private $testClassFilePath = null;

    /**
     *
     * @var array
     */
    private $testMethods = array();

    private function __construct() {}

    private function __clone() {}

    /**
     *
     * @return \PHPUnit\Framework\ClassMethodTest\ParseInfo
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function reset()
    {
        self::$instance = null;
    }

    public function getGeneratedClassName()
    {
        return $this->generatedClassName;
    }

    public function setGeneratedClassName($generatedClassName)
    {
        $this->generatedClassName = $generatedClassName;
    }

    public function getGeneratedClassFilePath()
    {
        return $this->generatedClassFilePath;
    }

    public function setGeneratedClassFilePath($generatedClassFilePath)
    {
        $this->generatedClassFilePath = $generatedClassFilePath;
    }

    public function getTestClassName()
    {
        return $this->testClassName;
    }

    public function setTestClassName($testClassName)
    {
        $this->testClassName = $testClassName;
    }

    public function getTestClassFilePath()
    {
        return $this->testClassFilePath;
    }

    public function setTestClassFilePath($testClassFilePath)
    {
        $this->testClassFilePath = $testClassFilePath;
    }

    public function getTestMethods()
    {
        return $this->testMethods;
    }

    public function setTestMethods(array $testMethods)
    {
        $this->testMethods = $testMethods;
    }


}
