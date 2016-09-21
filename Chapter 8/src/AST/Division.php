<?php
namespace Packt\Chp8\DSL\AST;

class Division extends BinaryOperation
{
    public function evaluate(array $variables = [])
    {
        return $this->left->evaluate($variables) / $this->right->evaluate($variables);
    }

    public function compile(): string
    {
        return '(' . $this->left->compile() . ')/(' . $this->right->compile() . ')';
    }
}