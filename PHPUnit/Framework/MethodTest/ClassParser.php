<?php
/**
 * @author florianbiewald@gmail.com
 */
namespace PHPUnit\Framework\MethodTest;

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

    public function __construct($className)
    {
        $this->class = new \ReflectionClass($className);
        $filename = $this->class->getFileName();
        if (!file_exists($filename)) {
            throw new \PHPUnit_Framework_Exception('Class has no file: ' . $this->class->getName());
        }
        $this->sourceLines = file($filename);
    }

    public function getNamespace()
    {
        $ns = $this->class->getNamespaceName();
        return !empty($ns)
               ? "namespace $ns;"
               : '';
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

    private function makeMethodPublic($line)
    {
        $functionPos = stripos($line, 'function');
        $stringUntilFunction = substr($line, 0, $functionPos);

        // replace protected/private with public
        $publicModifier = str_ireplace(array('private', 'protected'), 'public', $stringUntilFunction);

        return substr_replace($line, $publicModifier, 0, $functionPos);
    }
}
