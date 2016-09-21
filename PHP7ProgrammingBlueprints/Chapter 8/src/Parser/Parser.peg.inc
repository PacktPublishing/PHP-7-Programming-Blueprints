<?php
namespace Packt\Chp8\DSL\Parser;

use hafriedlander\Peg\Parser\Basic;
use hafriedlander\Peg\Parser\Packrat;
use Packt\Chp8\DSL\AST\Integer;
use Packt\Chp8\DSL\AST\Decimal;
use Packt\Chp8\DSL\AST\Multiplication;
use Packt\Chp8\DSL\AST\NamedVariable;
use Packt\Chp8\DSL\AST\PropertyFetch;
use Packt\Chp8\DSL\AST\Addition;
use Packt\Chp8\DSL\AST\Subtraction;
use Packt\Chp8\DSL\AST\Division;
use Packt\Chp8\DSL\AST\Equals;
use Packt\Chp8\DSL\AST\NotEquals;
use Packt\Chp8\DSL\AST\GreaterThan;
use Packt\Chp8\DSL\AST\LessThan;
use Packt\Chp8\DSL\AST\LogicalAnd;
use Packt\Chp8\DSL\AST\LogicalOr;
use Packt\Chp8\DSL\AST\Condition;

/**
 * This is the grammar definition for the PEG-Parser.
 *
 * Generate the actual parser by running the following command:
 *
 *     php -d pcre.jit=0 vendor/hafriedlander/php-peg/cli.php src/Parser/Parser.peg.inc > src/Parser/Parser.php
 *
 * @package Packt\Chp8\DSL\Parser
 */
class Parser extends Basic
{
    /*!* ExpressionLanguage

    Integer: value:/-?[0-9]+/
        function value(array &$result, array $sub) {
            $result['node'] = new Integer((int) $sub['text']);
        }

    Decimal: value:/-?[0-9]*\.[0-9]+/
        function value(array &$result, array $sub) {
            $result['node']  = new Decimal((float) $sub['text']);
        }

    Number: Decimal | Integer
        function Decimal(&$result, $sub) { $result['node']  = $sub['node']; }
        function Integer(&$result, $sub) { $result['node']  = $sub['node']; }
    
    Name: /[a-zA-Z]+/

    Variable: Name ('.' property:Name)*
        function Name(&$result, $sub) { $result['node'] = new NamedVariable($sub['text']); }
        function property(&$result, $sub) {
            $result['node'] = new PropertyFetch($result['node'], $sub['text']);
        }

    Value: Number | Variable | '(' > NumExpr > ')'
        function Number(array &$result, array $sub) { $result['node'] = $sub['node']; }
        function Variable(array &$result, array $sub) { $result['node'] = $sub['node']; }
        function NumExpr(array &$result, array $sub) { $result['node'] = $sub['node']; }

    Product: left:Value (operand:(> operator:('*'|'/') > right:Value))*
        function left(&$result, $sub) { $result['node']  = $sub['node']; }
        function right(&$result, $sub) { $result['node']  = $sub['node']; }
        function operator(&$result, $sub) { $result['operator'] = $sub['text']; }
        function operand(&$result, $sub) {
            if ($sub['operator'] == '*') {
                $result['node'] = new Multiplication($result['node'], $sub['node']);
            } else {
                $result['node'] = new Division($result['node'], $sub['node']);
            }
        }

    Sum: left:Product (operand:(> operator:('+'|'-') > right:Product))*
        function left(&$result, $sub) { $result['node']  = $sub['node']; }
        function right(&$result, $sub) { $result['node']  = $sub['node']; }
        function operator(&$result, $sub) { $result['operator'] = $sub['text']; }
        function operand(&$result, $sub) {
            if ($sub['operator'] == '+') {
                $result['node'] = new Addition($result['node'], $sub['node']);
            } else {
                $result['node'] = new Subtraction($result['node'], $sub['node']);
            }
        }

    NumExpr: Sum
        function Sum(&$result, $sub) { $result['node'] = $sub['node']; }

    ComparisonOperator: "=" | "|=" | ">=" | ">" | "<=" | "<"

    BoolValue: Comparison | '(' > BoolExpr > ')'
        function Comparison(&$res, $sub) { $res['node'] = $sub['node']; }
        function BoolExpr(&$res, $sub) { $res['node'] = $sub['node']; }

    Comparison: left:NumExpr (operand:(> op:ComparisonOperator > right:NumExpr))
        function left(&$result, $sub) { $result['leftNode'] = $sub['node']; }
        function right(array &$result, array $sub) { $result['node'] = $sub['node']; }
        function op(array &$result, array $sub) { $result['op'] = $sub['text']; }
        function operand(&$result, $sub) {
            if ($sub['op'] == '=') {
                $result['node'] = new Equals($result['leftNode'], $sub['node']);
            } else if ($sub['op'] == '|=') {
                $result['node'] = new NotEquals($result['leftNode'], $sub['node']);
            } else if ($sub['op'] == '>') {
                $result['node'] = new GreaterThan($result['leftNode'], $sub['node']);
            } else if ($sub['op'] == '>=') {
                $result['node'] = new LogicalOr(
                    new GreaterThan($result['leftNode'], $sub['node']),
                    new Equals($result['leftNode'], $sub['node'])
                );
            } else if ($sub['op'] == '<') {
                $result['node'] = new LessThan($result['leftNode'], $sub['node']);
            } else if ($sub['op'] == '<=') {
                $result['node'] = new LogicalOr(
                    new LessThan($result['leftNode'], $sub['node']),
                    new Equals($result['leftNode'], $sub['node'])
                );
            }
        }
    
    And: left:BoolValue (> "and" > right:BoolValue)*
        function left(&$res, $sub) { $res['node'] = $sub['node']; }
        function right(&$res, $sub) { $res['node'] = new LogicalAnd($res['node'], $sub['node']); }

    Or: left:And (> "or" > right:And)*
        function left(&$res, $sub) { $res['node'] = $sub['node']; }
        function right(&$res, $sub) { $res['node'] = new LogicalOr($res['node'], $sub['node']); }
    
    Condition: "when" > when:BoolExpr > "then" > then:Expr > "else" > else:Expr
        function when(&$res, $sub) { $res['when'] = $sub['node']; }
        function then(&$res, $sub) { $res['then'] = $sub['node']; }
        function else(&$res, $sub) { $res['node'] = new Condition($res['when'], $res['then'], $sub['node']); }

    BoolExpr: Condition | Or | Comparison
        function Condition(&$result, $sub) { $result['node'] = $sub['node']; }
        function Or(&$result, $sub) { $result['node'] = $sub['node']; }
        function Comparison(&$result, $sub) { $result['node'] = $sub['node']; }

    Expr: BoolExpr | NumExpr
        function NumExpr(&$result, $sub) { $result['node'] = $sub['node']; }
        function BoolExpr(&$result, $sub) { $result['node'] = $sub['node']; }

    */
}
