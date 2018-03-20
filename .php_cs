<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('CodeGeneration/Fixtures')
    ->in(__DIR__.'/src/');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'binary_operator_spaces' => ['align_double_arrow' => true],
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'no_alias_functions' => true,
        'not_operator_with_space' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
