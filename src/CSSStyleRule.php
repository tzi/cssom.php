<?php

namespace CSSOM;

class CSSStyleRule extends CSSRule
{

    /**
     * @var string
     */
    public $selectorText;

    /**
     * @readonly
     * @var CSSStyleDeclaration
     */
    public $style;


    public function __construct($cssText, $parent)
    {
        parent::__construct($cssText, $parent);
        $this->selectorText = (new Parser)->parseCSSSelector($cssText);
        $this->style = (new Parser)->parseCSSStyle($cssText);
    }
}