<?php
namespace Packt\Chp8\DSL;

use Packt\Chp8\DSL\AST\Expression;

/**
 * Expression builder that compiles expressions as PHP code and re-uses them when necessary.
 *
 * @package Packt\Chp8\DSL
 */
class CompilingExpressionBuilder
{
    /** @var string */
    private $cacheDir;

    /** @var ExpressionBuilder */
    private $inner;

    /**
     * CompilingExpressionBuilder constructor.
     *
     * @param ExpressionBuilder $inner The "real" expression builder
     * @param string            $cacheDir The cache directory in which to store compiled expressions
     */
    public function __construct(ExpressionBuilder $inner, string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
        $this->inner = $inner;
    }

    /**
     * Parses an expression
     *
     * @param string $expr The expression string to parse
     * @return Expression The parsed expression
     */
    public function parseExpression(string $expr): Expression
    {
        $cacheKey = sha1($expr);
        $cacheFile = $this->cacheDir . '/' . $cacheKey . '.php';
        if (file_exists($cacheFile)) {
            return include($cacheFile);
        }

        $expr = $this->inner->parseExpression($expr);

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }

        file_put_contents($cacheFile, '<?php return new class implements '.Expression::class.' {
            public function evaluate(array $variables=[]) {
                return ' . $expr->compile() . ';
            }
            
            public function compile(): string {
                return ' . var_export($expr->compile(), true) . ';
            }
        };');
        return $expr;
    }
}