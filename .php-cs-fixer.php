<?php

use PhpCsFixer\Config;

$finder = PhpCsFixer\Finder::create()
    ->exclude('CodeGeneration/Fixtures')
    ->in(__DIR__.'/src/');

return (new Config())
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'no_alias_functions' => true,
        'not_operator_with_space' => true,
        'return_type_declaration' => true,
        'phpdoc_to_return_type' => true,
        'binary_operator_spaces' => false,
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'void_return' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['const', 'class', 'function'],
        ],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
