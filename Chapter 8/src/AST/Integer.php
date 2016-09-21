<?php
namespace Packt\Chp8\DSL\AST;

class Integer extends Number
{
    /**
     * @var int
     */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function evaluate(array $variables = []): int
    {
        return $this->value;
    }
}