<?php

require(__DIR__ . '/vendor/autoload.php');

(new CSSOM\CSSParser)->parse(__DIR__ . '/test/resources/oneRule.css');

$sampler = new Hoa\Compiler\Llk\Sampler\Coverage(
    // Grammar.
    Hoa\Compiler\Llk\Llk::load(new Hoa\File\Read(__DIR__ . '/pp/css.pp')),
    // Token sampler.
    new Hoa\Regex\Visitor\Isotropic(new Hoa\Math\Sampler\Random())
);

foreach ($sampler as $i => $data) {
    echo $i, ' => ', $data, "\n";
}