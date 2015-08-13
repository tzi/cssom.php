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
        $buffer = 0;
        $level = 0;
        $caret = 0;
        while ($caret < strlen($cssText)) {
            if ($cssText[$caret] == '}') {
                if ($level == 0) {
                    $this->addError('Closing bracket without an opening one.', $cssText, $caret);
                    $caret++;
                    $buffer = $caret;
                    continue;
                }
                $level--;
                if ($level == 0) {
                    $caret++;
                    $cssTextRules[] = substr($cssText, $buffer, $caret);
                    $buffer = $caret;
                    continue;
                }
            } else if ($cssText[$caret] == '{') {
                $level++;
            }
            $caret++;
        }
        if (trim(substr($cssText, $buffer)) != '') {
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