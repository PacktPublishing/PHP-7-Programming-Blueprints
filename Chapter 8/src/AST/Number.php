<?php
namespace Packt\Chp8\DSL\AST;

abstract class Number implements Expression
{
    public function compile(): string
    {
        return var_export($this->evaluate(), true);
    }

}