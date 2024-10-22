<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in('src')
    ->in('tests')
    ->exclude([
        'Workspace',
    ])
;

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'no_unused_imports' => true,
        'final_class' => true,
        'phpdoc_to_property_type' => true,
        'no_superfluous_phpdoc_tags' => [
            'remove_inheritdoc' => true,
            'allow_mixed' => true,
        ],
        'class_attributes_separation' => [
            'elements' => [
                'const' => 'only_if_meta',
                'property' => 'one',
                'trait_import' => 'only_if_meta',
            ],
        ],
        'no_empty_phpdoc' => true,
        'phpdoc_trim' => true,
        'array_syntax' => ['syntax' => 'short'],
        'list_syntax' => ['syntax' => 'short'],
        'void_return' => true,
        'ordered_class_elements' => true,
        'single_quote' => true,
        'heredoc_indentation' => true,
        'global_namespace_import' => true,
        'no_trailing_whitespace' => true,
        'no_whitespace_in_blank_line' => true,
        'new_with_parentheses' => [
            'anonymous_class' => true,
            'named_class' => true,
        ],
    ])
    ->setFinder($finder)
;

