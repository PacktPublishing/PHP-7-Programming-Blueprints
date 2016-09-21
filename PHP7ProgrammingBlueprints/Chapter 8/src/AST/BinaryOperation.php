<?php
namespace Packt\Chp8\DSL\AST;

abstract class BinaryOperation implements Expression
{
    /** @var Expression */
    protected $left;

    /** @var Expression */
    protected $right;

    public function __construct(Expression $left, Expression $right)
    {
        $this->left = $left;
        $this->right = $right;
    }
}