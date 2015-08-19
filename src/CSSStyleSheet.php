<?php

namespace CSSOM;

class CSSStyleSheet
{

    /**
     * @readonly
     * @var CSSRuleList
     */
    public $cssRules;

    /**
     * @readonly
     * @var array
     */
    public $errors = [];


    /* PUBLIC METHODS
     *************************************************************************/
    public function __construct()
    {
        $this->cssRules = new CSSRuleList();
    }

    /**
     * @param string $cssText
     * @return self
     */
    public function parse($cssText)
    {
        $cssTextRules = $this->parseCSSTextRules($cssText);
        foreach ($cssTextRules as $cssTextRule) {
            $this->cssRules[] = CSSRule::newInstance($cssTextRule['cssText'], $cssTextRule['position'], $this);
        }
        return $this;
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function parseCSSTextRules($cssText)
    {
        $cssTextRules = [];
        $buffer = 0;
        $bracketLevel = 0;
        $parenthesisLevel = 0;
        $caret = 0;
        $startingQuoteList = ['"', "'"];
        $endingQuoteList = ['"', "'"];
        $currentQuoteIndex = null;
        $quoteCaret = null;
        while ($caret < strlen($cssText)) {
            if (is_null($currentQuoteIndex)) {
                if ($cssText[$caret] == '}') {
                    if ($bracketLevel == 0) {
                        $this->addError('Closing bracket without an opening one.', $cssText, $caret);
                        $caret++;
                        $buffer = $caret;
                        continue;
                    }
                    $bracketLevel--;
                    if ($bracketLevel == 0) {
                        $position = $this->getPosition($cssText, $buffer);
                        $caret++;
                        $cssTextRule = substr($cssText, $buffer, $caret);
                        $cssTextRules[] = ['cssText' => $cssTextRule, 'position' => $position];
                        $buffer = $caret;
                        continue;
                    }
                } else if ($cssText[$caret] == '{') {
                    $bracketLevel++;
                } else if ($cssText[$caret] == ')') {
                    if ($parenthesisLevel == 0) {
                        $this->addError('Closing parenthesis without an opening one.', $cssText, $caret);
                        $caret++;
                        $buffer = $caret;
                        continue;
                    }
                    $parenthesisLevel--;
                } else if ($cssText[$caret] == '(') {
                    $parenthesisLevel++;
                } else if ($quoteIndex = array_search($cssText[$caret], $startingQuoteList)) {
                    $currentQuoteIndex = $quoteIndex;
                    $quoteCaret = $caret;
                }
            } else if ($cssText[$caret] == $endingQuoteList[$currentQuoteIndex]) {
                $currentQuoteIndex = null;
            }
            $caret++;
        }
        if (!is_null($currentQuoteIndex)) {
            $this->addError('Missing closing quote '.$currentQuoteIndex, $cssText, $quoteCaret);
        }
        if ($bracketLevel > 0) {
            $this->addError('Missing closing bracket.', $cssText, strlen($cssText));
        }
        if ($parenthesisLevel > 0) {
            $this->addError('Missing closing parenthesis.', $cssText, strlen($cssText));
        }
        return $cssTextRules;
    }

    protected function addError($message, $cssText, $caret) {
        $position = $this->getPosition($cssText, $caret);
        $this->errors[] = array_merge(['message' => $message], $position);
    }

    protected function getPosition($cssText, $caret) {
        $beforeText = substr($cssText, 0, $caret);
        $line = substr_count($beforeText, PHP_EOL) + 1;
        $col = $caret + 1;
        $lastEOL = strrpos($beforeText, PHP_EOL);
        if ($lastEOL !== false) {
            $col = $col - $lastEOL - 1;
        }
        return ['line' => $line, 'col' => $col];
    }
}