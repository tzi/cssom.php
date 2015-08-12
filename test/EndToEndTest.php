<?php

namespace CSSOM\Test;

use CSSOM\CSSStyleSheet;

class EndToEndTest extends \PHPUnit_Framework_TestCase
{

    public function testOneRule()
    {
        $oneRule = file_get_contents(__DIR__.'/resources/oneRule.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($oneRule);
        $this->assertEquals($oneRule, $cssStyleSheet->cssRules[0]->cssText);
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
    }
}