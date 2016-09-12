<?php
require_once('vendor/autoload.php');
$header = <<<EOF
Fuel

Fuel is a fast, lightweight, community driven PHP5 framework.

@package    Fuel
@version    1.8
@author     Fuel Development Team
@license    MIT License
@copyright  2010 - 2014 Fuel Development Team
@link       http://fuelphp.com
EOF;

/**
 * http://kore1server.com/334/Laravel%E3%81%A7CS+Fixer%E3%82%92%E4%BD%BF%E3%81%86
 */
Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('core')
    ->exclude('packages')
    ->exclude('vendor')
    ->exclude('config')
    ->exclude('views')
    ->exclude('logs')
    ->exclude('cache')
    ->in(__DIR__);

$fixers = [
    '-braces',
    'combine_consecutive_unsets',
    'header_comment',
    'long_array_syntax',
    'no_useless_else',
    'no_useless_return',
    'ordered_use',
    'php_unit_construct',
    'php_unit_strict',
    'strict',
    'strict_param',
];

$result = Symfony\CS\Config\Config::create()
    //->level(Symfony\CS\FIXERINTERFACE::PSR2_LEVEL)
    ->level(Symfony\CS\FIXERINTERFACE::CONTRIB_LEVEL)
    ->fixers($fixers)
    ->finder($finder);
    // ->setUsingCache(true);

return $result;
