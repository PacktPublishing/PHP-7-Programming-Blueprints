<?php

use Packt\Chp8\DSL\CompilingExpressionBuilder;
use Packt\Chp8\DSL\ExpressionBuilder;
use Packt\Chp8\DSL\Parser\PackratParser;

require 'vendor/autoload.php';

/**
 * @Revs(5000)
 * @-Iterations(2)
 */
class ParserBenchmark
{
    public function benchSimpleExpressionBasicParser()
    {
        $builder = new ExpressionBuilder();
        $builder
            ->parseExpression('a = 2')
            ->evaluate(['a' => 1]);
    }
    public function benchSimpleExpressionCompilingParser()
    {
        $builder = new CompilingExpressionBuilder(new ExpressionBuilder(), 'cache/auto');
        $builder
            ->parseExpression('a = 2')
            ->evaluate(['a' => 1]);
    }
    public function benchComplexExpressionBasicParser()
    {
        $builder = new ExpressionBuilder();
        $builder
            ->parseExpression('when (customer.age = 1 and cart.value = 200) then cart.value * 0.1 else cart.value * 0.2')
            ->evaluate(['customer' => ['age' => 1], 'cart' => ['value' => 200]]);
    }
    public function benchComplexExpressionCompilingParser()
    {
        $builder = new CompilingExpressionBuilder(new ExpressionBuilder(), 'cache/auto');
        $builder
            ->parseExpression('when (customer.age = 1 and cart.value = 200) then cart.value * 0.1 else cart.value * 0.2')
            ->evaluate(['customer' => ['age' => 1], 'cart' => ['value' => 200]]);
    }
}