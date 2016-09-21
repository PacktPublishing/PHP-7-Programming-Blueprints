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

class Parser extends Basic
{
    /* Integer: value:/-?[0-9]+/ */
    protected $match_Integer_typestack = array('Integer');
    function match_Integer ($stack = array()) {
    	$matchrule = "Integer"; $result = $this->construct($matchrule, $matchrule, null);
    	$stack[] = $result; $result = $this->construct( $matchrule, "value" ); 
    	if (( $subres = $this->rx( '/-?[0-9]+/' ) ) !== FALSE) {
    		$result["text"] .= $subres;
    		$subres = $result; $result = array_pop($stack);
    		$this->store( $result, $subres, 'value' );
    		return $this->finalise($result);
    	}
    	else {
    		$result = array_pop($stack);
    		return FALSE;
    	}
    }

public function Integer_value (array &$result, array $sub) {
        $result['node'] = new Integer((int) $sub['text']);
    }

    /* Decimal: value:/-?[0-9]*\.[0-9]+/ */
    protected $match_Decimal_typestack = array('Decimal');
    function match_Decimal ($stack = array()) {
    	$matchrule = "Decimal"; $result = $this->construct($matchrule, $matchrule, null);
    	$stack[] = $result; $result = $this->construct( $matchrule, "value" ); 
    	if (( $subres = $this->rx( '/-?[0-9]*\.[0-9]+/' ) ) !== FALSE) {
    		$result["text"] .= $subres;
    		$subres = $result; $result = array_pop($stack);
    		$this->store( $result, $subres, 'value' );
    		return $this->finalise($result);
    	}
    	else {
    		$result = array_pop($stack);
    		return FALSE;
    	}
    }

public function Decimal_value (array &$result, array $sub) {
        $result['node']  = new Decimal((float) $sub['text']);
    }

    /* Number: Decimal | Integer */
    protected $match_Number_typestack = array('Number');
    function match_Number ($stack = array()) {
    	$matchrule = "Number"; $result = $this->construct($matchrule, $matchrule, null);
    	$_5 = NULL;
    	do {
    		$res_2 = $result;
    		$pos_2 = $this->pos;
    		$matcher = 'match_'.'Decimal'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres );
    			$_5 = TRUE; break;
    		}
    		$result = $res_2;
    		$this->pos = $pos_2;
    		$matcher = 'match_'.'Integer'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres );
    			$_5 = TRUE; break;
    		}
    		$result = $res_2;
    		$this->pos = $pos_2;
    		$_5 = FALSE; break;
    	}
    	while(0);
    	if( $_5 === TRUE ) { return $this->finalise($result); }
    	if( $_5 === FALSE) { return FALSE; }
    }

public function Number_Decimal (&$result, $sub) { $result['node']  = $sub['node']; }

public function Number_Integer (&$result, $sub) { $result['node']  = $sub['node']; }

    /* Name: /[a-zA-Z]+/ */
    protected $match_Name_typestack = array('Name');
    function match_Name ($stack = array()) {
    	$matchrule = "Name"; $result = $this->construct($matchrule, $matchrule, null);
    	if (( $subres = $this->rx( '/[a-zA-Z]+/' ) ) !== FALSE) {
    		$result["text"] .= $subres;
    		return $this->finalise($result);
    	}
    	else { return FALSE; }
    }


    /* Variable: Name ('.' property:Name)* */
    protected $match_Variable_typestack = array('Variable');
    function match_Variable ($stack = array()) {
    	$matchrule = "Variable"; $result = $this->construct($matchrule, $matchrule, null);
    	$_13 = NULL;
    	do {
    		$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres );
    		}
    		else { $_13 = FALSE; break; }
    		while (true) {
    			$res_12 = $result;
    			$pos_12 = $this->pos;
    			$_11 = NULL;
    			do {
    				if (substr($this->string,$this->pos,1) == '.') {
    					$this->pos += 1;
    					$result["text"] .= '.';
    				}
    				else { $_11 = FALSE; break; }
    				$matcher = 'match_'.'Name'; $key = $matcher; $pos = $this->pos;
    				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    				if ($subres !== FALSE) {
    					$this->store( $result, $subres, "property" );
    				}
    				else { $_11 = FALSE; break; }
    				$_11 = TRUE; break;
    			}
    			while(0);
    			if( $_11 === FALSE) {
    				$result = $res_12;
    				$this->pos = $pos_12;
    				unset( $res_12 );
    				unset( $pos_12 );
    				break;
    			}
    		}
    		$_13 = TRUE; break;
    	}
    	while(0);
    	if( $_13 === TRUE ) { return $this->finalise($result); }
    	if( $_13 === FALSE) { return FALSE; }
    }

public function Variable_Name (&$result, $sub) { $result['node'] = new NamedVariable($sub['text']); }

public function Variable_property (&$result, $sub) {
        $result['node'] = new PropertyFetch($result['node'], $sub['text']);
    }

    /* Value: Number | Variable | '(' > NumExpr > ')' */
    protected $match_Value_typestack = array('Value');
    function match_Value ($stack = array()) {
    	$matchrule = "Value"; $result = $this->construct($matchrule, $matchrule, null);
    	$_28 = NULL;
    	do {
    		$res_15 = $result;
    		$pos_15 = $this->pos;
    		$matcher = 'match_'.'Number'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres );
    			$_28 = TRUE; break;
    		}
    		$result = $res_15;
    		$this->pos = $pos_15;
    		$_26 = NULL;
    		do {
    			$res_17 = $result;
    			$pos_17 = $this->pos;
    			$matcher = 'match_'.'Variable'; $key = $matcher; $pos = $this->pos;
    			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    			if ($subres !== FALSE) {
    				$this->store( $result, $subres );
    				$_26 = TRUE; break;
    			}
    			$result = $res_17;
    			$this->pos = $pos_17;
    			$_24 = NULL;
    			do {
    				if (substr($this->string,$this->pos,1) == '(') {
    					$this->pos += 1;
    					$result["text"] .= '(';
    				}
    				else { $_24 = FALSE; break; }
    				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    				$matcher = 'match_'.'NumExpr'; $key = $matcher; $pos = $this->pos;
    				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    				if ($subres !== FALSE) {
    					$this->store( $result, $subres );
    				}
    				else { $_24 = FALSE; break; }
    				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    				if (substr($this->string,$this->pos,1) == ')') {
    					$this->pos += 1;
    					$result["text"] .= ')';
    				}
    				else { $_24 = FALSE; break; }
    				$_24 = TRUE; break;
    			}
    			while(0);
    			if( $_24 === TRUE ) { $_26 = TRUE; break; }
    			$result = $res_17;
    			$this->pos = $pos_17;
    			$_26 = FALSE; break;
    		}
    		while(0);
    		if( $_26 === TRUE ) { $_28 = TRUE; break; }
    		$result = $res_15;
    		$this->pos = $pos_15;
    		$_28 = FALSE; break;
    	}
    	while(0);
    	if( $_28 === TRUE ) { return $this->finalise($result); }
    	if( $_28 === FALSE) { return FALSE; }
    }

public function Value_Number (array &$result, array $sub) { $result['node'] = $sub['node']; }

public function Value_Variable (array &$result, array $sub) { $result['node'] = $sub['node']; }

public function Value_NumExpr (array &$result, array $sub) { $result['node'] = $sub['node']; }

    /* Product: left:Value (operand:(> operator:('*'|'/') > right:Value))* */
    protected $match_Product_typestack = array('Product');
    function match_Product ($stack = array()) {
    	$matchrule = "Product"; $result = $this->construct($matchrule, $matchrule, null);
    	$_45 = NULL;
    	do {
    		$matcher = 'match_'.'Value'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres, "left" );
    		}
    		else { $_45 = FALSE; break; }
    		while (true) {
    			$res_44 = $result;
    			$pos_44 = $this->pos;
    			$_43 = NULL;
    			do {
    				$stack[] = $result; $result = $this->construct( $matchrule, "operand" ); 
    				$_41 = NULL;
    				do {
    					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    					$stack[] = $result; $result = $this->construct( $matchrule, "operator" ); 
    					$_37 = NULL;
    					do {
    						$_35 = NULL;
    						do {
    							$res_32 = $result;
    							$pos_32 = $this->pos;
    							if (substr($this->string,$this->pos,1) == '*') {
    								$this->pos += 1;
    								$result["text"] .= '*';
    								$_35 = TRUE; break;
    							}
    							$result = $res_32;
    							$this->pos = $pos_32;
    							if (substr($this->string,$this->pos,1) == '/') {
    								$this->pos += 1;
    								$result["text"] .= '/';
    								$_35 = TRUE; break;
    							}
    							$result = $res_32;
    							$this->pos = $pos_32;
    							$_35 = FALSE; break;
    						}
    						while(0);
    						if( $_35 === FALSE) { $_37 = FALSE; break; }
    						$_37 = TRUE; break;
    					}
    					while(0);
    					if( $_37 === TRUE ) {
    						$subres = $result; $result = array_pop($stack);
    						$this->store( $result, $subres, 'operator' );
    					}
    					if( $_37 === FALSE) {
    						$result = array_pop($stack);
    						$_41 = FALSE; break;
    					}
    					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    					$matcher = 'match_'.'Value'; $key = $matcher; $pos = $this->pos;
    					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    					if ($subres !== FALSE) {
    						$this->store( $result, $subres, "right" );
    					}
    					else { $_41 = FALSE; break; }
    					$_41 = TRUE; break;
    				}
    				while(0);
    				if( $_41 === TRUE ) {
    					$subres = $result; $result = array_pop($stack);
    					$this->store( $result, $subres, 'operand' );
    				}
    				if( $_41 === FALSE) {
    					$result = array_pop($stack);
    					$_43 = FALSE; break;
    				}
    				$_43 = TRUE; break;
    			}
    			while(0);
    			if( $_43 === FALSE) {
    				$result = $res_44;
    				$this->pos = $pos_44;
    				unset( $res_44 );
    				unset( $pos_44 );
    				break;
    			}
    		}
    		$_45 = TRUE; break;
    	}
    	while(0);
    	if( $_45 === TRUE ) { return $this->finalise($result); }
    	if( $_45 === FALSE) { return FALSE; }
    }

public function Product_left (&$result, $sub) { $result['node']  = $sub['node']; }

public function Product_right (&$result, $sub) { $result['node']  = $sub['node']; }

public function Product_operator (&$result, $sub) { $result['operator'] = $sub['text']; }

public function Product_operand (&$result, $sub) {
        if ($sub['operator'] == '*') {
            $result['node'] = new Multiplication($result['node'], $sub['node']);
        } else {
            $result['node'] = new Division($result['node'], $sub['node']);
        }
    }

    /* Sum: left:Product (operand:(> operator:('+'|'-') > right:Product))* */
    protected $match_Sum_typestack = array('Sum');
    function match_Sum ($stack = array()) {
    	$matchrule = "Sum"; $result = $this->construct($matchrule, $matchrule, null);
    	$_62 = NULL;
    	do {
    		$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres, "left" );
    		}
    		else { $_62 = FALSE; break; }
    		while (true) {
    			$res_61 = $result;
    			$pos_61 = $this->pos;
    			$_60 = NULL;
    			do {
    				$stack[] = $result; $result = $this->construct( $matchrule, "operand" ); 
    				$_58 = NULL;
    				do {
    					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    					$stack[] = $result; $result = $this->construct( $matchrule, "operator" ); 
    					$_54 = NULL;
    					do {
    						$_52 = NULL;
    						do {
    							$res_49 = $result;
    							$pos_49 = $this->pos;
    							if (substr($this->string,$this->pos,1) == '+') {
    								$this->pos += 1;
    								$result["text"] .= '+';
    								$_52 = TRUE; break;
    							}
    							$result = $res_49;
    							$this->pos = $pos_49;
    							if (substr($this->string,$this->pos,1) == '-') {
    								$this->pos += 1;
    								$result["text"] .= '-';
    								$_52 = TRUE; break;
    							}
    							$result = $res_49;
    							$this->pos = $pos_49;
    							$_52 = FALSE; break;
    						}
    						while(0);
    						if( $_52 === FALSE) { $_54 = FALSE; break; }
    						$_54 = TRUE; break;
    					}
    					while(0);
    					if( $_54 === TRUE ) {
    						$subres = $result; $result = array_pop($stack);
    						$this->store( $result, $subres, 'operator' );
    					}
    					if( $_54 === FALSE) {
    						$result = array_pop($stack);
    						$_58 = FALSE; break;
    					}
    					if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    					$matcher = 'match_'.'Product'; $key = $matcher; $pos = $this->pos;
    					$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    					if ($subres !== FALSE) {
    						$this->store( $result, $subres, "right" );
    					}
    					else { $_58 = FALSE; break; }
    					$_58 = TRUE; break;
    				}
    				while(0);
    				if( $_58 === TRUE ) {
    					$subres = $result; $result = array_pop($stack);
    					$this->store( $result, $subres, 'operand' );
    				}
    				if( $_58 === FALSE) {
    					$result = array_pop($stack);
    					$_60 = FALSE; break;
    				}
    				$_60 = TRUE; break;
    			}
    			while(0);
    			if( $_60 === FALSE) {
    				$result = $res_61;
    				$this->pos = $pos_61;
    				unset( $res_61 );
    				unset( $pos_61 );
    				break;
    			}
    		}
    		$_62 = TRUE; break;
    	}
    	while(0);
    	if( $_62 === TRUE ) { return $this->finalise($result); }
    	if( $_62 === FALSE) { return FALSE; }
    }

public function Sum_left (&$result, $sub) { $result['node']  = $sub['node']; }

public function Sum_right (&$result, $sub) { $result['node']  = $sub['node']; }

public function Sum_operator (&$result, $sub) { $result['operator'] = $sub['text']; }

public function Sum_operand (&$result, $sub) {
        if ($sub['operator'] == '+') {
            $result['node'] = new Addition($result['node'], $sub['node']);
        } else {
            $result['node'] = new Subtraction($result['node'], $sub['node']);
        }
    }

    /* NumExpr: Sum */
    protected $match_NumExpr_typestack = array('NumExpr');
    function match_NumExpr ($stack = array()) {
    	$matchrule = "NumExpr"; $result = $this->construct($matchrule, $matchrule, null);
    	$matcher = 'match_'.'Sum'; $key = $matcher; $pos = $this->pos;
    	$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    	if ($subres !== FALSE) {
    		$this->store( $result, $subres );
    		return $this->finalise($result);
    	}
    	else { return FALSE; }
    }

public function NumExpr_Sum (&$result, $sub) { $result['node'] = $sub['node']; }

    /* ComparisonOperator: "=" | "|=" | ">=" | ">" | "<=" | "<" */
    protected $match_ComparisonOperator_typestack = array('ComparisonOperator');
    function match_ComparisonOperator ($stack = array()) {
    	$matchrule = "ComparisonOperator"; $result = $this->construct($matchrule, $matchrule, null);
    	$_84 = NULL;
    	do {
    		$res_65 = $result;
    		$pos_65 = $this->pos;
    		if (substr($this->string,$this->pos,1) == '=') {
    			$this->pos += 1;
    			$result["text"] .= '=';
    			$_84 = TRUE; break;
    		}
    		$result = $res_65;
    		$this->pos = $pos_65;
    		$_82 = NULL;
    		do {
    			$res_67 = $result;
    			$pos_67 = $this->pos;
    			if (( $subres = $this->literal( '|=' ) ) !== FALSE) {
    				$result["text"] .= $subres;
    				$_82 = TRUE; break;
    			}
    			$result = $res_67;
    			$this->pos = $pos_67;
    			$_80 = NULL;
    			do {
    				$res_69 = $result;
    				$pos_69 = $this->pos;
    				if (( $subres = $this->literal( '>=' ) ) !== FALSE) {
    					$result["text"] .= $subres;
    					$_80 = TRUE; break;
    				}
    				$result = $res_69;
    				$this->pos = $pos_69;
    				$_78 = NULL;
    				do {
    					$res_71 = $result;
    					$pos_71 = $this->pos;
    					if (substr($this->string,$this->pos,1) == '>') {
    						$this->pos += 1;
    						$result["text"] .= '>';
    						$_78 = TRUE; break;
    					}
    					$result = $res_71;
    					$this->pos = $pos_71;
    					$_76 = NULL;
    					do {
    						$res_73 = $result;
    						$pos_73 = $this->pos;
    						if (( $subres = $this->literal( '<=' ) ) !== FALSE) {
    							$result["text"] .= $subres;
    							$_76 = TRUE; break;
    						}
    						$result = $res_73;
    						$this->pos = $pos_73;
    						if (substr($this->string,$this->pos,1) == '<') {
    							$this->pos += 1;
    							$result["text"] .= '<';
    							$_76 = TRUE; break;
    						}
    						$result = $res_73;
    						$this->pos = $pos_73;
    						$_76 = FALSE; break;
    					}
    					while(0);
    					if( $_76 === TRUE ) { $_78 = TRUE; break; }
    					$result = $res_71;
    					$this->pos = $pos_71;
    					$_78 = FALSE; break;
    				}
    				while(0);
    				if( $_78 === TRUE ) { $_80 = TRUE; break; }
    				$result = $res_69;
    				$this->pos = $pos_69;
    				$_80 = FALSE; break;
    			}
    			while(0);
    			if( $_80 === TRUE ) { $_82 = TRUE; break; }
    			$result = $res_67;
    			$this->pos = $pos_67;
    			$_82 = FALSE; break;
    		}
    		while(0);
    		if( $_82 === TRUE ) { $_84 = TRUE; break; }
    		$result = $res_65;
    		$this->pos = $pos_65;
    		$_84 = FALSE; break;
    	}
    	while(0);
    	if( $_84 === TRUE ) { return $this->finalise($result); }
    	if( $_84 === FALSE) { return FALSE; }
    }


    /* BoolValue: Comparison | '(' > BoolExpr > ')' */
    protected $match_BoolValue_typestack = array('BoolValue');
    function match_BoolValue ($stack = array()) {
    	$matchrule = "BoolValue"; $result = $this->construct($matchrule, $matchrule, null);
    	$_95 = NULL;
    	do {
    		$res_86 = $result;
    		$pos_86 = $this->pos;
    		$matcher = 'match_'.'Comparison'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres );
    			$_95 = TRUE; break;
    		}
    		$result = $res_86;
    		$this->pos = $pos_86;
    		$_93 = NULL;
    		do {
    			if (substr($this->string,$this->pos,1) == '(') {
    				$this->pos += 1;
    				$result["text"] .= '(';
    			}
    			else { $_93 = FALSE; break; }
    			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    			$matcher = 'match_'.'BoolExpr'; $key = $matcher; $pos = $this->pos;
    			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    			if ($subres !== FALSE) {
    				$this->store( $result, $subres );
    			}
    			else { $_93 = FALSE; break; }
    			if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    			if (substr($this->string,$this->pos,1) == ')') {
    				$this->pos += 1;
    				$result["text"] .= ')';
    			}
    			else { $_93 = FALSE; break; }
    			$_93 = TRUE; break;
    		}
    		while(0);
    		if( $_93 === TRUE ) { $_95 = TRUE; break; }
    		$result = $res_86;
    		$this->pos = $pos_86;
    		$_95 = FALSE; break;
    	}
    	while(0);
    	if( $_95 === TRUE ) { return $this->finalise($result); }
    	if( $_95 === FALSE) { return FALSE; }
    }

public function BoolValue_Comparison (&$res, $sub) { $res['node'] = $sub['node']; }

public function BoolValue_BoolExpr (&$res, $sub) { $res['node'] = $sub['node']; }

    /* Comparison: left:NumExpr (operand:(> op:ComparisonOperator > right:NumExpr)) */
    protected $match_Comparison_typestack = array('Comparison');
    function match_Comparison ($stack = array()) {
    	$matchrule = "Comparison"; $result = $this->construct($matchrule, $matchrule, null);
    	$_106 = NULL;
    	do {
    		$matcher = 'match_'.'NumExpr'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres, "left" );
    		}
    		else { $_106 = FALSE; break; }
    		$_104 = NULL;
    		do {
    			$stack[] = $result; $result = $this->construct( $matchrule, "operand" ); 
    			$_102 = NULL;
    			do {
    				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    				$matcher = 'match_'.'ComparisonOperator'; $key = $matcher; $pos = $this->pos;
    				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    				if ($subres !== FALSE) {
    					$this->store( $result, $subres, "op" );
    				}
    				else { $_102 = FALSE; break; }
    				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    				$matcher = 'match_'.'NumExpr'; $key = $matcher; $pos = $this->pos;
    				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    				if ($subres !== FALSE) {
    					$this->store( $result, $subres, "right" );
    				}
    				else { $_102 = FALSE; break; }
    				$_102 = TRUE; break;
    			}
    			while(0);
    			if( $_102 === TRUE ) {
    				$subres = $result; $result = array_pop($stack);
    				$this->store( $result, $subres, 'operand' );
    			}
    			if( $_102 === FALSE) {
    				$result = array_pop($stack);
    				$_104 = FALSE; break;
    			}
    			$_104 = TRUE; break;
    		}
    		while(0);
    		if( $_104 === FALSE) { $_106 = FALSE; break; }
    		$_106 = TRUE; break;
    	}
    	while(0);
    	if( $_106 === TRUE ) { return $this->finalise($result); }
    	if( $_106 === FALSE) { return FALSE; }
    }

public function Comparison_left (&$result, $sub) { $result['leftNode'] = $sub['node']; }

public function Comparison_right (array &$result, array $sub) { $result['node'] = $sub['node']; }

public function Comparison_op (array &$result, array $sub) { $result['op'] = $sub['text']; }

public function Comparison_operand (&$result, $sub) {
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

    /* And: left:BoolValue (> "and" > right:BoolValue)* */
    protected $match_And_typestack = array('And');
    function match_And ($stack = array()) {
    	$matchrule = "And"; $result = $this->construct($matchrule, $matchrule, null);
    	$_115 = NULL;
    	do {
    		$matcher = 'match_'.'BoolValue'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres, "left" );
    		}
    		else { $_115 = FALSE; break; }
    		while (true) {
    			$res_114 = $result;
    			$pos_114 = $this->pos;
    			$_113 = NULL;
    			do {
    				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    				if (( $subres = $this->literal( 'and' ) ) !== FALSE) { $result["text"] .= $subres; }
    				else { $_113 = FALSE; break; }
    				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    				$matcher = 'match_'.'BoolValue'; $key = $matcher; $pos = $this->pos;
    				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    				if ($subres !== FALSE) {
    					$this->store( $result, $subres, "right" );
    				}
    				else { $_113 = FALSE; break; }
    				$_113 = TRUE; break;
    			}
    			while(0);
    			if( $_113 === FALSE) {
    				$result = $res_114;
    				$this->pos = $pos_114;
    				unset( $res_114 );
    				unset( $pos_114 );
    				break;
    			}
    		}
    		$_115 = TRUE; break;
    	}
    	while(0);
    	if( $_115 === TRUE ) { return $this->finalise($result); }
    	if( $_115 === FALSE) { return FALSE; }
    }

public function And_left (&$res, $sub) { $res['node'] = $sub['node']; }

public function And_right (&$res, $sub) { $res['node'] = new LogicalAnd($res['node'], $sub['node']); }

    /* Or: left:And (> "or" > right:And)* */
    protected $match_Or_typestack = array('Or');
    function match_Or ($stack = array()) {
    	$matchrule = "Or"; $result = $this->construct($matchrule, $matchrule, null);
    	$_124 = NULL;
    	do {
    		$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres, "left" );
    		}
    		else { $_124 = FALSE; break; }
    		while (true) {
    			$res_123 = $result;
    			$pos_123 = $this->pos;
    			$_122 = NULL;
    			do {
    				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    				if (( $subres = $this->literal( 'or' ) ) !== FALSE) { $result["text"] .= $subres; }
    				else { $_122 = FALSE; break; }
    				if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    				$matcher = 'match_'.'And'; $key = $matcher; $pos = $this->pos;
    				$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    				if ($subres !== FALSE) {
    					$this->store( $result, $subres, "right" );
    				}
    				else { $_122 = FALSE; break; }
    				$_122 = TRUE; break;
    			}
    			while(0);
    			if( $_122 === FALSE) {
    				$result = $res_123;
    				$this->pos = $pos_123;
    				unset( $res_123 );
    				unset( $pos_123 );
    				break;
    			}
    		}
    		$_124 = TRUE; break;
    	}
    	while(0);
    	if( $_124 === TRUE ) { return $this->finalise($result); }
    	if( $_124 === FALSE) { return FALSE; }
    }

public function Or_left (&$res, $sub) { $res['node'] = $sub['node']; }

public function Or_right (&$res, $sub) { $res['node'] = new LogicalOr($res['node'], $sub['node']); }

    /* Condition: "when" > when:BoolExpr > "then" > then:Expr > "else" > else:Expr */
    protected $match_Condition_typestack = array('Condition');
    function match_Condition ($stack = array()) {
    	$matchrule = "Condition"; $result = $this->construct($matchrule, $matchrule, null);
    	$_137 = NULL;
    	do {
    		if (( $subres = $this->literal( 'when' ) ) !== FALSE) { $result["text"] .= $subres; }
    		else { $_137 = FALSE; break; }
    		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    		$matcher = 'match_'.'BoolExpr'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres, "when" );
    		}
    		else { $_137 = FALSE; break; }
    		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    		if (( $subres = $this->literal( 'then' ) ) !== FALSE) { $result["text"] .= $subres; }
    		else { $_137 = FALSE; break; }
    		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    		$matcher = 'match_'.'Expr'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres, "then" );
    		}
    		else { $_137 = FALSE; break; }
    		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    		if (( $subres = $this->literal( 'else' ) ) !== FALSE) { $result["text"] .= $subres; }
    		else { $_137 = FALSE; break; }
    		if (( $subres = $this->whitespace(  ) ) !== FALSE) { $result["text"] .= $subres; }
    		$matcher = 'match_'.'Expr'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres, "else" );
    		}
    		else { $_137 = FALSE; break; }
    		$_137 = TRUE; break;
    	}
    	while(0);
    	if( $_137 === TRUE ) { return $this->finalise($result); }
    	if( $_137 === FALSE) { return FALSE; }
    }

public function Condition_when (&$res, $sub) { $res['when'] = $sub['node']; }

public function Condition_then (&$res, $sub) { $res['then'] = $sub['node']; }

public function Condition_else (&$res, $sub) { $res['node'] = new Condition($res['when'], $res['then'], $sub['node']); }

    /* BoolExpr: Condition | Or | Comparison */
    protected $match_BoolExpr_typestack = array('BoolExpr');
    function match_BoolExpr ($stack = array()) {
    	$matchrule = "BoolExpr"; $result = $this->construct($matchrule, $matchrule, null);
    	$_146 = NULL;
    	do {
    		$res_139 = $result;
    		$pos_139 = $this->pos;
    		$matcher = 'match_'.'Condition'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres );
    			$_146 = TRUE; break;
    		}
    		$result = $res_139;
    		$this->pos = $pos_139;
    		$_144 = NULL;
    		do {
    			$res_141 = $result;
    			$pos_141 = $this->pos;
    			$matcher = 'match_'.'Or'; $key = $matcher; $pos = $this->pos;
    			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    			if ($subres !== FALSE) {
    				$this->store( $result, $subres );
    				$_144 = TRUE; break;
    			}
    			$result = $res_141;
    			$this->pos = $pos_141;
    			$matcher = 'match_'.'Comparison'; $key = $matcher; $pos = $this->pos;
    			$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    			if ($subres !== FALSE) {
    				$this->store( $result, $subres );
    				$_144 = TRUE; break;
    			}
    			$result = $res_141;
    			$this->pos = $pos_141;
    			$_144 = FALSE; break;
    		}
    		while(0);
    		if( $_144 === TRUE ) { $_146 = TRUE; break; }
    		$result = $res_139;
    		$this->pos = $pos_139;
    		$_146 = FALSE; break;
    	}
    	while(0);
    	if( $_146 === TRUE ) { return $this->finalise($result); }
    	if( $_146 === FALSE) { return FALSE; }
    }

public function BoolExpr_Condition (&$result, $sub) { $result['node'] = $sub['node']; }

public function BoolExpr_Or (&$result, $sub) { $result['node'] = $sub['node']; }

public function BoolExpr_Comparison (&$result, $sub) { $result['node'] = $sub['node']; }

    /* Expr: BoolExpr | NumExpr */
    protected $match_Expr_typestack = array('Expr');
    function match_Expr ($stack = array()) {
    	$matchrule = "Expr"; $result = $this->construct($matchrule, $matchrule, null);
    	$_151 = NULL;
    	do {
    		$res_148 = $result;
    		$pos_148 = $this->pos;
    		$matcher = 'match_'.'BoolExpr'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres );
    			$_151 = TRUE; break;
    		}
    		$result = $res_148;
    		$this->pos = $pos_148;
    		$matcher = 'match_'.'NumExpr'; $key = $matcher; $pos = $this->pos;
    		$subres = ( $this->packhas( $key, $pos ) ? $this->packread( $key, $pos ) : $this->packwrite( $key, $pos, $this->$matcher(array_merge($stack, array($result))) ) );
    		if ($subres !== FALSE) {
    			$this->store( $result, $subres );
    			$_151 = TRUE; break;
    		}
    		$result = $res_148;
    		$this->pos = $pos_148;
    		$_151 = FALSE; break;
    	}
    	while(0);
    	if( $_151 === TRUE ) { return $this->finalise($result); }
    	if( $_151 === FALSE) { return FALSE; }
    }

public function Expr_NumExpr (&$result, $sub) { $result['node'] = $sub['node']; }

public function Expr_BoolExpr (&$result, $sub) { $result['node'] = $sub['node']; }


}
