<?php

namespace CSSOM;

class CSSStyleSheet
{

    /**
     * @readonly
     * @var CSSRuleList
     */
    public $cssRules;

    public function __construct()
    {
        $this->cssRules = new CSSRuleList();
    }

    public function parse($cssText)
    {
        $cssTextRules = (new Parser)->parseCSSTextRules($cssText);
        foreach ($cssTextRules as $cssTextRule) {
            $this->cssRules[] = CSSRule::newInstance($cssTextRule, $this);
        }
        return $this;
    }
}