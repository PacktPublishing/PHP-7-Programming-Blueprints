<?php
namespace Packt\Chp8\DSL\Tests\Functional;

use Packt\Chp8\DSL\Exception\ParsingException;
use Packt\Chp8\DSL\ExpressionBuilder;
use PHPUnit_Framework_TestCase as TestCase;

class ParserTest extends TestCase
{
    /** @var ExpressionBuilder */
    private $builder;

    public function setUp()
    {
        $this->builder = new ExpressionBuilder();
    }

    public function dataForValidExpressions()
    {
        yield ['1', 1];
        yield ['1+1', 2];
        yield ['1 + 1', 2];
        //yield ["1 +    1", 2];
        yield ['2 * 3', 6];
        yield ['2 + 3 * 4', 14];
        yield ['(2 + 3) * 4', 20];
        yield ['4 * (2 + 3)', 20];
        yield ['a', 5, ['a' => 5]];
        yield ['a * 2', 10, ['a' => 5]];
        yield ['a + 2', 7, ['a' => 5]];
        yield ['1 = 1', true];
        yield ['1 = 2', false];
        yield ['1 |= 1', false];
        yield ['1 |= 2', true];
        yield ['2 * 5 = 3 + 7', true];
        yield ['foo.bar', 123, ['foo' => ['bar' => 123]]];
        yield ['when 1=2 then 3 else 4', 4];
        yield ['when a=2 then 3 else 4', 4, ['a' => 1]];
        yield ['when a=2 then 3 else 4', 3, ['a' => 2]];
        yield ['when foo.bar = 2 then 3 else 4', 3, ['foo' => ['bar' => 2]]];
        yield ['when foo.bar = 2 then 3 else foo.bar * 2', 6, ['foo' => ['bar' => 3]]];
        yield ['when foo.bar = 2 and 1=1 then 3 else foo.bar * 2', 6, ['foo' => ['bar' => 3]]];
        yield [
            'when customer.age = 1 and cart.value = 200 then cart.value * 0.1 else cart.value * 0.2', 20,
            ['customer' => ['age' => 1], 'cart' => ['value' => 200]]];
        yield ['1 < 2', true];
        yield ['1 < -1', false];
        yield ['1 > 2', false];
        yield ['1 > -1', true];
        yield ['1 >= 2', false];
        yield ['1 >= -1', true];
        yield ['1 >= 1', true];
        yield ['1 <= 2', true];
        yield ['1 <= -1', false];
        yield ['1 <= 1', true];
    }

    /**
     * @param       $expression
     * @param       $expectedResult
     * @param array $variables
     * @throws ParsingException
     * @dataProvider dataForValidExpressions
     */
    public function testExpressionYieldsCorrectResult($expression, $expectedResult, $variables = [])
    {
        $expr = $this->builder->parseExpression($expression);
        assertThat($expr->evaluate($variables), equalTo($expectedResult));
    }
}