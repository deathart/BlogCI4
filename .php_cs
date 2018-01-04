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
        'ordered_imports' => true,
        'single_quote' => true,
        'blank_line_after_namespace' => true,
        'normalize_index_brace' => true,
        'blank_line_before_statement' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'no_empty_statement' => true,
        'align_multiline_comment' => true,
        'declare_equal_normalize' => ['space' => 'single'],
        'method_separation' => true,
        'protected_to_private' => true,
        'simplified_null_return' => true,
        'single_class_element_per_statement' => true,
        'ternary_to_null_coalescing' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'whitespace_after_comma_in_array' => true
    ])
    ->setFinder($finder);