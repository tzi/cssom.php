<?php

namespace CSSOM;

class CSSParser
{

    public function parse($file)
    {
        $tokenizer = new \Hoa\File\Read(__DIR__ . '/../pp/css.pp');
        $compiler = \Hoa\Compiler\Llk\Llk::load($tokenizer);
        $ast = $compiler->parse(file_get_contents($file));
        $dump = new \Hoa\Compiler\Visitor\Dump();
        echo $dump->visit($ast);
    }
}