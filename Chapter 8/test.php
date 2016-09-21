<?php
use Packt\Chp8\DSL\CompilingExpressionBuilder;
use Packt\Chp8\DSL\ExpressionBuilder;
use Packt\Chp8\DSL\Parser\Parser;

require 'vendor/autoload.php';

$builder = new CompilingExpressionBuilder(new ExpressionBuilder(), 'cache');

var_dump($builder->parseExpression('1 * 2')->evaluate());
var_dump($builder->parseExpression('2 * a')->evaluate(['a' => 4]));
var_dump($builder->parseExpression('a = 2')->evaluate(['a' => 2]));
var_dump($builder->parseExpression('a = 2')->evaluate(['a' => 4]));
var_dump($builder->parseExpression('a = 2 or b = 4')->evaluate(['a' => 4, 'b' => 4]));
var_dump($builder->parseExpression('foo.bar')->evaluate(['foo' => ['bar' => 123]]));
var_dump($builder->parseExpression('(5 + 3.14) * (14 + (29 - 2 * 3.918))')->evaluate(['a' => 12]));
var_dump($builder
    ->parseExpression('when cart.value >= 200 then cart.value * 0.15 else cart.value * 0.2')
    ->evaluate(['cart' => new class {
        public function getValue() { return 200; }
    }])
);
