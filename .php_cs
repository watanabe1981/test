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

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->exclude('fuel/core/*')
    ->exclude('fuel/packages/*')
    ->exclude('fuel/vendor/*')
    ->exclude('fuel/app/config/*')
    ->exclude('fuel/app/views/*')
    ->exclude('fuel/app/lang/*')
    ->exclude('fuel/app/logs/*')
    ->exclude('fuel/app/cache/*')
    ->in(__DIR__);

Symfony\CS\Fixer\Contrib\HeaderCommentFixer::setHeader($header);

$fixers = [
    '-psr0', // PSR-0を向こうにしないと、名前空間の戦闘がappに書き換えられる
    'align_equals', // = の位置揃え
    'combine_consecutive_unsets',
    'header_comment',
    '-short_tag', // PHPのコードはショートタグの使用を強制
    '-short_array_syntax',
    'indentation', // TABを4スペースに置き換える
    'linefeed', // 改行コードをLFに
    'trailing_spaces', // 行の末尾の空白を除去する
    'php_closing_tag', // ファイルがPHPのみのときは
    'visibility', // アクセス権はすべてつけないとだめ
    '-braces',
    //'+braces',
    '-eof_ending', // ファイルの末尾は一行の空白で終わる
    'align_equals', // = の位置揃え
    'single_quote', // シングルクォートでの囲みを強制
    '-long_array_syntax',
    '-elseif', // else if ではなく elseif を使用する
    'no_useless_else',
    'no_useless_return',
    'ordered_use',
    'php_unit_construct',
    'php_unit_strict',
    'strict',
    'strict_param',
    '-single_line_after_imports',
    'controls_spaces',
    'return', // リターンの前は一行開ける
    'ereg_to_preg',
    'array_element_no_space_before_comma',
    'array_element_white_space_after_comma',
    'logical_not_operators_with_spaces',
];

$result = Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FIXERINTERFACE::PSR2_LEVEL)
    //->level(Symfony\CS\FIXERINTERFACE::CONTRIB_LEVEL)
    ->fixers($fixers)
    ->finder($finder);
    // ->setUsingCache(true);

return $result;
