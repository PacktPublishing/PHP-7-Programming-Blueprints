<?php
namespace Packt\Chp8\DSL\AST;

class Condition implements Expression
{
    /** @var Expression */
    private $when;

    /** @var Expression */
    private $then;

    /** @var Expression */
    private $else;

    public function __construct(Expression $when, Expression $then, Expression $else)
    {
        $this->when = $when;
        $this->then = $then;
        $this->else = $else;
    }

    public function evaluate(array $variables = [])
    {
        if ($this->when->evaluate($variables)) {
            return $this->then->evaluate($variables);
        }
        return $this->else->evaluate($variables);
    }

    public function compile(): string
    {
        return $this->when->compile() . '?(' . $this->then->compile() . '):(' . $this->else->compile() . ')';
    }

}