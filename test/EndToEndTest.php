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
        $this->assertCount(1, $cssStyleSheet->cssRules, 'One rule parsed');
        $this->assertEquals($cssText, $cssStyleSheet->cssRules[0]->cssText);
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
        $this->assertEquals(1, $cssStyleSheet->cssRules[0]->position['line'], 'The first rule is at line 1');
        $this->assertEquals(1, $cssStyleSheet->cssRules[0]->position['col'], 'The first rule is at column 1');
    }

    public function testMissingBracket()
    {
        $cssText = file_get_contents(__DIR__.'/resources/errorMissingBracket.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        $this->assertCount(1, $cssStyleSheet->errors, 'One parsing error');
        $this->assertCount(1, $cssStyleSheet->cssRules, 'One rule parsed');
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
        $this->assertCount(2, $cssStyleSheet->cssRules, 'Two rule parsed');
        $this->assertEquals(3, $cssStyleSheet->errors[0]['line'], 'The parsing error is at line 3');
        $this->assertEquals(2, $cssStyleSheet->errors[0]['col'], 'The parsing error is at column 2');
        $this->assertEquals('body', trim($cssStyleSheet->cssRules[1]->selectorText));
        $this->assertEquals('background: white;', trim($cssStyleSheet->cssRules[1]->style));
        $this->assertEquals(3, $cssStyleSheet->cssRules[1]->position['line'], 'The second rule is at line 3');
        $this->assertEquals(3, $cssStyleSheet->cssRules[1]->position['col'], 'The second rule is at column 3');
    }
}