<?php
namespace Packt\Chp8\DSL\AST;

interface Expression
{
    public function evaluate(array $variables = []);

    public function compile(): string;
}