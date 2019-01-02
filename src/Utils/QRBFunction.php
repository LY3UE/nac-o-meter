<?php

namespace App\Utils;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;


class QRBFunction extends FunctionNode
{
    public $firstWwlSquare = null;
    public $secondWwlSquare = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstWwlSquare = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondWwlSquare = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'QRB(' .
            $this->firstWwlSquare->dispatch($sqlWalker) . ', ' .
            $this->secondWwlSquare->dispatch($sqlWalker) .
        ')';
    }

}
