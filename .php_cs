<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/application')
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'list_syntax' => ['syntax' => 'short'],
        'array_syntax' => ['syntax' => 'short'],
        'single_quote' => true,
        'blank_line_after_namespace' => true,
        'normalize_index_brace' => true
    ])
    ->setFinder($finder);