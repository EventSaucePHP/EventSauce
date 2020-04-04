<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('CodeGeneration/Fixtures')
    ->in(__DIR__.'/src/');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'no_alias_functions' => true,
        'not_operator_with_space' => true,
        'return_type_declaration' => true,
        'phpdoc_to_return_type' => true,
        'void_return' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['const', 'class', 'function'],
        ],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
