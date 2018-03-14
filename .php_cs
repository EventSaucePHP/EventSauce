<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('CodeGeneration/Fixtures')
    ->in(__DIR__.'/src/');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'not_operator_with_space' => true,
        'binary_operator_spaces' => ['align_double_arrow' => true],
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);