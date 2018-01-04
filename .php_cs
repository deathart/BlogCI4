<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/application')
;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        'psr4' => true,
        '@PSR2' => true,
        'list_syntax' => ['syntax' => 'short'],
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'single_quote' => true,
        'blank_line_after_namespace' => true,
        'single_blank_line_before_namespace' => false,
        'normalize_index_brace' => true,
        'blank_line_before_statement' => true
    ])
    ->setFinder($finder);