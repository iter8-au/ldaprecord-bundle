<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->append([__FILE__]);

// @See https://github.com/FriendsOfPHP/PHP-CS-Fixer
$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHPUnit75Migration:risky' => true,
        'php_unit_dedicate_assert' => ['target' => '5.6'],
        'array_syntax' => ['syntax' => 'short'],
        'fopen_flags' => false,
        'protected_to_private' => false,
        'native_constant_invocation' => true,
        'combine_nested_dirname' => true,
        'list_syntax' => ['syntax' => 'short'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
