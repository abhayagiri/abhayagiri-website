<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('app/Legacy')
    ->exclude('app/Legacy.php')
    ->exclude('attic')
    ->exclude('bower_components')
    ->exclude('bootstrap')
    ->exclude('config/gravatar.php')
    ->exclude('database/migrations')
    ->exclude('legacy')
    ->exclude('mix')
    ->exclude('new')
    ->exclude('node_modules')
    ->exclude('public')
    ->exclude('storage')
    ->exclude('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);
;

return PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRules([
        // PSR12 rules. See https://github.com/guzzle/guzzle/issues/2498
        // The following rules that are commented out are done to err on the
        // side of being conservative.
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_opening_tag' => true,
        // 'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => false,
        // 'function_to_constant' => ['functions' => ['php_sapi_name']],
        'fully_qualified_strict_types' => true,
        'header_comment' => false,
        'lowercase_cast' => true,
        // 'is_null' => ['use_yoda_style' => false],
        'magic_method_casing' => true,
        // 'modernize_types_casting' => true,
        'multiline_comment_opening_closing' => true,
        // 'native_constant_invocation' => true,
        // 'no_alias_functions' => true,
        'no_alternative_syntax' => true,
        'no_blank_lines_after_phpdoc' => true,
        // 'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_extra_blank_lines' => true,
        'no_leading_import_slash' => true,
        'no_spaces_around_offset' => true,
        // 'no_superfluous_phpdoc_tags' => ['allow_mixed' => true],
        'no_trailing_comma_in_singleline_array' => true,
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        // 'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_imports' => true,
        // 'php_unit_mock' => ['target' => 'newest'],
        // 'php_unit_dedicate_assert_internal_type' => ['target' => 'newest'],
        // 'php_unit_expectation' => ['target' => 'newest'],
        // 'php_unit_no_expectation_annotation' => ['target' => 'newest'],
        // 'php_unit_test_annotation' => ['style' => 'prefix'],
        // 'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
        // 'php_unit_mock_short_will_return' => true,
        // 'phpdoc_align' => ['align' => 'vertical'],
        'phpdoc_line_span' => ['method' => 'multi', 'property' => 'multi'],
        'phpdoc_scalar' => true,
        'phpdoc_separation' => true,
        'phpdoc_single_line_var_spacing' => true,
        // 'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'phpdoc_types_order' => ['null_adjustment' => 'always_last', 'sort_algorithm' => 'none'],
        'phpdoc_var_without_name' => true,
        'short_scalar_cast' => true,
        'single_trait_insert_per_statement' => true,
        'standardize_not_equals' => true,
        'visibility_required' => true,
        'yoda_style' => false,
    ])
;
