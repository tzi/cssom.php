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


    /* PUBLIC METHODS
     *************************************************************************/
    public function __construct($cssText, $parent)
    {
        parent::__construct($cssText, $parent);
        $this->selectorText = $this->parseCSSSelector($cssText);
        $this->style = $this->parseCSSStyle($cssText);
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function parseCSSSelector($cssText)
    {
        return substr($cssText, 0, strpos($cssText, '{'));
    }

    protected function parseCSSStyle($cssText)
    {
        $start = strpos($cssText, '{') + 1;
        return substr($cssText, $start, strpos($cssText, '}') - $start);
    }
}