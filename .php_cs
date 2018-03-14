<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src/')
    ->exclude(__DIR__.'/src/CodeGeneration/Fixtures');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
    ])
    ->setFinder($finder);