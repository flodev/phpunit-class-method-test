<?php
/**
 * @author Florian Biewald <f.biewald@gmail.com>
 */
namespace PHPUnit\Framework\ClassMethodTest;

class ClassParser
{
    /**
     *
     * @var \ReflectionClass
     */
    private $class = null;

    /**
     *
     * @var array
     */
    private $sourceLines = null;

    /**
     *
     * @param string $className
     * @throws \PHPUnit_Framework_Exception
     */
    public function __construct($className)
    {
        $this->class = new \ReflectionClass($className);
        $filename = $this->class->getFileName();
        if (!file_exists($filename)) {
            throw new \PHPUnit_Framework_Exception('Class has no file: ' . $this->class->getName());
        }
        $this->sourceLines = file($filename);
    }

    /**
     *
     * @return \ReflectionClass
     */
    public function getReflection()
    {
        return $this->class;
    }

    /**
     *
     * @return string
     */
    public function getNamespace()
    {
        $ns = $this->class->getNamespaceName();
        return !empty($ns)
               ? "namespace $ns;"
               : '';
    }

    /**
     *
     * @return array
     */
    public function extractProperties()
    {
        $props = array();
        $reflectionProperties = $this->getReflectionProperties();

        foreach ($this->class->getDefaultProperties() as $name => $value) {
            $reflectionProp = $reflectionProperties[$name];
            $propParts = \Reflection::getModifierNames($reflectionProp->getModifiers());
            array_push($propParts, $name, '=', $this->getPropValue($value) . ';');
            $props[] = implode(' ', $propParts);
        }

        return $props;
    }

    /**
     *
     * @param mixed $propValue
     * @return string
     */
    private function getPropValue($propValue)
    {
        if (is_string($propValue)) {
            return "'" . addslashes($propValue) . "'";
        }

        if (is_null($propValue)) {
            return 'null';
        }

        if (is_array($propValue)) {
            return var_export($propValue, true);
        }
        return $propValue;
    }

    /**
     *
     * @return array
     */
    public function getReflectionProperties()
    {
        $props = array();
        foreach ($this->class->getProperties() as $prop) {
            $props[$prop->getName()] = $prop;
        }
        return $props;
    }

    /**
     *
     * @param string $name
     * @return string
     */
    public function extractFunction($name)
    {
        $func = $this->class->getMethod($name);
        $startLine = $func->getStartLine() - 1;
        $endLine = $func->getEndLine();
        $length = $endLine - $startLine;

        $functionLines = array_slice($this->sourceLines, $startLine, $length);
        $functionLines[0] = $this->makeMethodPublic($functionLines[0]);

        $function = implode("", $functionLines);
        return $function;
    }

    /**
     *
     * @param string $line
     * @return string
     */
    private function makeMethodPublic($line)
    {
        $functionPos = stripos($line, 'function');
        $stringUntilFunction = substr($line, 0, $functionPos);

        // replace protected/private with public
        $publicModifier = str_ireplace(array('private', 'protected'), 'public', $stringUntilFunction);

        return substr_replace($line, $publicModifier, 0, $functionPos);
    }

    /**
     *
     * @return array
     */
    public function extractConstants()
    {
        $constants = array();
        foreach ($this->class->getConstants() as $name => $value) {
            $constants[] = 'const ' . $name . ' = ' . $this->getPropValue($value) . ';';
        }
        return $constants;
    }
}
