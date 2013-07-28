<?php
/**
 * @author florianbiewald@gmail.com
 */

namespace PHPUnit\Framework\MethodTest;


class MethodDefinition
{
    /**
     *
     * @var string
     */
    private $body = null;

    /**
     *
     * @var string
     */
    private $definition = null;

    /**
     *
     * @var string
     */
    private $modifier = null;

    static private function getBody()
    {
        return $this->body;
    }

    protected function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getDefinition()
    {
        return $this->definition;
    }

    public function setDefinition($definition)
    {
        $this->definition = $definition;
        return $this;
    }

    public function getModifier()
    {
        return $this->modifier;
    }

    public function setModifier($modifier)
    {
        $this->modifier = $modifier;
        return $this;
    }


}
