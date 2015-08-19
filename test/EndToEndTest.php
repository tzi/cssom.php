<?php

namespace CSSOM\Test;

use CSSOM\CSSStyleSheet;

class EndToEndTest extends \PHPUnit_Framework_TestCase
{

    public function testOneRule()
    {
        $cssText = file_get_contents(__DIR__.'/resources/oneRule.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        $this->assertCount(0, $cssStyleSheet->errors, 'Number of parsing errors');
        $this->assertCount(1, $cssStyleSheet->cssRules, 'Number of rules parsed');
        $this->assertEquals($cssText, $cssStyleSheet->cssRules[0]->cssText);
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
        $this->assertEquals(1, $cssStyleSheet->cssRules[0]->position['line'], 'Rule line');
        $this->assertEquals(1, $cssStyleSheet->cssRules[0]->position['col'], 'Rule column');
    }

    public function testErrorMissingBracket()
    {
        $cssText = file_get_contents(__DIR__.'/resources/errorMissingBracket.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        $this->assertCount(1, $cssStyleSheet->errors, 'Number of parsing errors');
        $this->assertCount(1, $cssStyleSheet->cssRules, 'Number of rules parsed');
        $this->assertEquals(6, $cssStyleSheet->errors[0]['line'], 'Error line');
        $this->assertEquals(1, $cssStyleSheet->errors[0]['col'], 'Error column');
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
    }

    public function testErrorFlyingCloseBracket()
    {
        $cssText = file_get_contents(__DIR__.'/resources/errorFlyingCloseBracket.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        $this->assertCount(1, $cssStyleSheet->errors, 'Number of parsing errors');
        $this->assertCount(2, $cssStyleSheet->cssRules, 'Number of rules parsed');
        $this->assertEquals(3, $cssStyleSheet->errors[0]['line'], 'Error line');
        $this->assertEquals(2, $cssStyleSheet->errors[0]['col'], 'Error column');
        $this->assertEquals('body', trim($cssStyleSheet->cssRules[1]->selectorText));
        $this->assertEquals('background: white;', trim($cssStyleSheet->cssRules[1]->style));
        $this->assertEquals(3, $cssStyleSheet->cssRules[1]->position['line'], 'Rule line');
        $this->assertEquals(3, $cssStyleSheet->cssRules[1]->position['col'], 'Rule column');
    }

    public function testErrorMissingQuote()
    {
        $cssText = file_get_contents(__DIR__.'/resources/errorMissingQuote.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        $this->assertCount(3, $cssStyleSheet->errors, 'Number of parsing errors');
        $this->assertCount(1, $cssStyleSheet->cssRules, 'Number of rules parsed');
        $this->assertEquals(5, $cssStyleSheet->errors[0]['line'], 'Error line');
        $this->assertEquals(21, $cssStyleSheet->errors[0]['col'], 'Error column');
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
    }

    public function testErrorMissingParenthesis()
    {
        $cssText = file_get_contents(__DIR__.'/resources/errorMissingParenthesis.css');
        $cssStyleSheet = (new CSSStyleSheet)->parse($cssText);
        var_dump($cssStyleSheet->cssRules);
        $this->assertCount(1, $cssStyleSheet->errors, 'Number of parsing errors');
        $this->assertCount(1, $cssStyleSheet->cssRules, 'Number of rules parsed');
        $this->assertEquals(7, $cssStyleSheet->errors[0]['line'], 'Error line');
        $this->assertEquals(1, $cssStyleSheet->errors[0]['col'], 'Error column');
        $this->assertEquals('html', trim($cssStyleSheet->cssRules[0]->selectorText));
        $this->assertEquals('top: 0;', trim($cssStyleSheet->cssRules[0]->style));
    }
}