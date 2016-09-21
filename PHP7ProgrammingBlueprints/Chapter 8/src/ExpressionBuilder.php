<?php
namespace Packt\Chp8\DSL;

use Packt\Chp8\DSL\AST\Expression;
use Packt\Chp8\DSL\Exception\ParsingException;
use Packt\Chp8\DSL\Parser\Parser;

/**
 * Expression builder that parses and creates expressions
 *
 * @package Packt\Chp8\DSL
 */
class ExpressionBuilder
{

    /**
     * Parses an expression
     *
     * @param string $expr The expression string to parse
     * @return Expression The parsed expression
     * @throws ParsingException
     */
    public function parseExpression(string $expr): Expression
    {
        /** @var Parser $parser */
        $parser = new Parser($expr);
        $result = $parser->match_Expr();

        if ($result === false) {
            throw new ParsingException('could not parse expression');
        }

        if ($result['text'] !== $expr) {
            throw new ParsingException('could only partially parse expression: ' . $result['text']);
        }

        return $result['node'];
    }
}