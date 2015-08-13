<?php

namespace CSSOM;

class CSSStyleSheet
{

    /**
     * @readonly
     * @var CSSRuleList
     */
    public $cssRules;


    /* PUBLIC METHODS
     *************************************************************************/
    public function __construct()
    {
        $this->cssRules = new CSSRuleList();
    }

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
        $level = 0;
        $caret = 0;
        while ($caret < strlen($cssText)) {
            if ($cssText[$caret] == '}') {
                if ($level == 0) {
                    throw new \RuntimeException('Closing brackets without opening one.');
                }
                $level--;
                if ($level == 0) {
                    $cssTextRules[] = substr($cssText, 0, $caret + 1);
                    $cssText = substr($cssText, $caret + 1);
                    $caret = 0;
                }
            } else if ($cssText[$caret] == '{') {
                $level++;
            }

            $caret++;
        }
        if (trim($cssText) != '') {
            throw new \RuntimeException('Missing closing brackets.');
        }
        return $cssTextRules;
    }
}