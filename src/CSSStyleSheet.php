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
            $this->cssRules[] = CSSRule::newInstance($cssTextRule, $this);
        }
        return $this;
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function parseCSSTextRules($cssText)
    {
        $cssTextRules = [];
        $buffer = $cssText;
        $level = 0;
        $caret = 0;
        while ($caret < strlen($buffer)) {
            if ($buffer[$caret] == '}') {
                if ($level == 0) {
                    $this->addError('Closing bracket without an opening one.', $cssText, strlen($cssText) - strlen($buffer) + $caret);
                    $caret++;
                    continue;
                }
                $level--;
                if ($level == 0) {
                    $cssTextRules[] = substr($buffer, 0, $caret + 1);
                    $buffer = substr($buffer, $caret + 1);
                    $caret = 0;
                    continue;
                }
            } else if ($buffer[$caret] == '{') {
                $level++;
            }
            $caret++;
        }
        if (trim($buffer) != '') {
            $this->addError('Missing closing bracket.', $cssText, strlen($cssText));
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
        $col = $caret - strrpos($beforeText, PHP_EOL);
        return ['line' => $line, 'col' => $col];
    }
}