<?php
namespace Packt\Chp8\DSL\AST;

use Packt\Chp8\DSL\Exception\UnknownVariableException;

class NamedVariable implements Variable
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function evaluate(array $variables = [])
    {
        if (isset($variables[$this->name])) {
            return $variables[$this->name];
        }
        throw new UnknownVariableException();
    }

    public function compile(): string
    {
        return '(new ' . __CLASS__ . '(' . var_export($this->name, true) . '))->evaluate($variables)';
    }
}