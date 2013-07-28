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

    public function __construct($className)
    {
        $this->class = new \ReflectionClass($className);
    }


    /**
     *
     * @param string $name
     * @return string
     */
    public function extractFunction($name)
    {
        $func = $this->class->getMethod($name);
        $filename = $func->getFileName();

        if (!file_exists($filename)) {
            throw new \PHPUnit_Framework_Exception('Class has no file: ' . $this->class->getName());
        }

        $startLine = $func->getStartLine() - 1;
        $endLine = $func->getEndLine();
        $length = $endLine - $startLine;

        $source = file($filename);
        $source[$startLine] = $this->makeMethodPublic($source[$startLine]);
        $body = implode("", array_slice($source, $startLine, $length));
        return $body;
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
