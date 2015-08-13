<?php

namespace CSSOM;

abstract class CSSRule
{
    const STYLE_RULE = 1;
    const CHARSET_RULE = 2; // historical
    const IMPORT_RULE = 3;
    const MEDIA_RULE = 4;
    const FONT_FACE_RULE = 5;
    const PAGE_RULE = 6;
    const MARGIN_RULE = 9;
    const NAMESPACE_RULE = 10;

    /**
     * @readonly
     * @var int
     */
    public $type;

    /**
     * @var string
     */
    public $cssText;

    /**
     * @var array
     */
    public $position;

    /**
     * @readonly
     * @var CSSRule
     */
    public $parentRule;

    /**
     * @readonly
     * @var CSSStyleSheet
     */
    public $parentStyleSheet;


    public function __construct($cssText, $position, $parent)
    {
        $this->cssText = $cssText;
        $this->position = $position;
        if (is_a($parent, get_class())) {
            $this->parentRule = $parent;
        } else if (is_a($parent, 'CSSOM\\CSSDocument')) {
            $this->parentStyleSheet = $parent;
        }
    }

    static public function newInstance($cssText, $position, $parent)
    {
        return new CSSStyleRule($cssText, $position, $parent);
    }
}

;