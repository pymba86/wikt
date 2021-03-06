<?php

namespace Wikt\Processor;

use Doctrine\Common\Lexer\AbstractLexer;

class Lexer extends AbstractLexer
{
    const T_NONE                = 1;
    const T_INTEGER             = 2;
    const T_STRING              = 3;
    const T_INPUT_PARAMETER     = 4;
    const T_FLOAT               = 5;
    const T_CLOSE_PARENTHESIS   = 6;
    const T_OPEN_PARENTHESIS    = 7;
    const T_COMMA               = 8;
    const T_DIVIDE              = 9;
    const T_DOT                 = 10;
    const T_EQUALS              = 11;
    const T_GREATER_THAN        = 12;
    const T_LOWER_THAN          = 13;
    const T_MINUS               = 14;
    const T_MULTIPLY            = 15;
    const T_NEGATE              = 16;
    const T_PLUS                = 17;
    const T_OPEN_CURLY_BRACE    = 18;
    const T_CLOSE_CURLY_BRACE   = 19;
    const T_COLON               = 20;

    const T_OPEN_BRACKET        = 100;
    const T_CLOSE_BRACKET       = 101;

    private $brackets = array();

    public function __construct(array $brackets)
    {
        $this->brackets = $brackets;
    }

    /**
     * @inheritdoc
     */
    protected function getCatchablePatterns()
    {
        return array(
            '[a-z_\\\][a-z0-9_\\\]*[a-z0-9_]{1}',
            '(?:[0-9]+(?:[\.][0-9]+)*)(?:e[+-]?[0-9]+)?',
            "'(?:[^']|''|')*'", // Паттерн исключает слова в кавычках (только). Доработка - |'
            '\?[0-9]*|[a-z_][a-z0-9_]*',
            '(?:' . preg_quote($this->brackets[0]) . ')',
            '(?:' . preg_quote($this->brackets[1]) . ')'
        );
    }

    /**
     * @inheritdoc
     */
    protected function getNonCatchablePatterns()
    {
        return array('\s+', '(.)');
    }

    /**
     * @inheritdoc
     */
    protected function getType(&$value)
    {
        switch (true) {
            // Заданные брекеты
            case ($value === $this->brackets[0]):
                return self::T_OPEN_BRACKET;
            case ($value === $this->brackets[1]):
                return self::T_CLOSE_BRACKET;
            // Знаки
            case ($value === '.'):
                return self::T_DOT;
            case ($value === ','):
                return self::T_COMMA;
            case ($value === '('):
                return self::T_OPEN_PARENTHESIS;
            case ($value === ')'):
                return self::T_CLOSE_PARENTHESIS;
            case ($value === '='):
                return self::T_EQUALS;
            case ($value === '>'):
                return self::T_GREATER_THAN;
            case ($value === '<'):
                return self::T_LOWER_THAN;
            case ($value === '+'):
                return self::T_PLUS;
            case ($value === '-'):
                return self::T_MINUS;
            case ($value === '*'):
                return self::T_MULTIPLY;
            case ($value === '/'):
                return self::T_DIVIDE;
            case ($value === '!'):
                return self::T_NEGATE;
            case ($value === '{'):
                return self::T_OPEN_CURLY_BRACE;
            case ($value === '}'):
                return self::T_CLOSE_CURLY_BRACE;
            case ($value === ':'):
                return self::T_COLON;
            case (is_string($value)):
                return self::T_STRING;
            default:
                return self::T_NONE;
        }
    }

    /**
     * @param $position
     * @param $length
     * @return string
     * @throws \ReflectionException
     */
    public function getInputBetweenPosition($position, $length)
    {
        $reflectionClass = new \ReflectionClass('Doctrine\Common\Lexer\AbstractLexer');
        $reflectionProperty = $reflectionClass->getProperty('input');
        $reflectionProperty->setAccessible(true);
        $input = $reflectionProperty->getValue($this);
        return mb_substr($input, $position, $length);
    }

}
