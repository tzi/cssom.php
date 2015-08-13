<?php

namespace CSSOM\Test;

use CSSOM\CSSStyleSheet;

class EndToEndTest extends \PHPUnit_Framework_TestCase
{

    public function testOneRule()
    {
        $cssText = file_get_contents(__DIR__.'/resources/oneRule.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        $this->assertCount(0, $cssStyleSheet->errors, 'No parsing error');
        $this->assertEquals($cssText, $cssStyleSheet->cssRules[0]->cssText);
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
    }

    public function testMissingBracket()
    {
        $cssText = file_get_contents(__DIR__.'/resources/errorMissingBracket.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        $this->assertCount(1, $cssStyleSheet->errors, 'One parsing error');
        $this->assertEquals(6, $cssStyleSheet->errors[0]['line'], 'The parsing error is at line 6');
        $this->assertEquals(1, $cssStyleSheet->errors[0]['col'], 'The parsing error is at column 1');
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
    }

    public function testFlyingCloseBracket()
    {
        $cssText = file_get_contents(__DIR__.'/resources/errorFlyingCloseBracket.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        $this->assertCount(1, $cssStyleSheet->errors, 'One parsing error');
        $this->assertEquals(3, $cssStyleSheet->errors[0]['line'], 'The parsing error is at line 3');
        $this->assertEquals(2, $cssStyleSheet->errors[0]['col'], 'The parsing error is at column 2');
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
    }
}