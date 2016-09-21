<?php
namespace Packt\Chp8\DSL\AST;

class Decimal extends Number
{
    /** @var float */
    private $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function evaluate(array $variables = []): float
    {
        return $this->value;
    }
}