<?php
namespace Packt\Chp8\DSL\AST;

class PropertyFetch implements Variable
{
    /** @var Variable */
    private $left;

    /** @var string */
    private $property;

    public function __construct(Variable $left, string $property)
    {
        $this->left = $left;
        $this->property = $property;
    }

    public function evaluate(array $variables = [])
    {
        $var = $this->left->evaluate($variables);
        return static::evaluateStatic($var, $this->property);
    }

    public static function evaluateStatic($var, string $property)
    {
        if (is_object($var)) {
            $getterMethodName = 'get' . ucfirst($property);
            if (is_callable([$var, $getterMethodName])) {
                return $var->{$getterMethodName}();
            }

            $isMethodName = 'is' . ucfirst($property);
            if (is_callable([$var, $isMethodName])) {
                return $var->{$isMethodName}();
            }

            return $var->{$property} ?? null;
        }
        return $var[$property] ?? null;
    }

    public function compile(): string
    {
        return __CLASS__ . '::evaluateStatic(' . $this->left->compile() . ', ' . var_export($this->property, true) . ')';
    }
}