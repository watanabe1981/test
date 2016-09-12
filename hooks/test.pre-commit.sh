#!/bin/bash - 
#===============================================================================
#
#          FILE: test.pre-commit.sh
# 
#         USAGE: ./test.pre-commit.sh 
# 
#   DESCRIPTION: 
# 
#       OPTIONS: ---
#  REQUIREMENTS: ---
#          BUGS: ---
#         NOTES: ---
#        AUTHOR: YOUR NAME (), 
#  ORGANIZATION: 
#       CREATED: 2016/09/12 10時14分30秒 JST
#      REVISION:  ---
#===============================================================================

IS_ERROR=0
# コミットされるファイルのうち、.phpで終わるもの
for FILE in `git status -uno --short | grep -E '^[AUM].*.php$'| cut -c3-`; do
    # シンタックスのチェック
    if php -l $FILE; then
        # PHPMDで未使用変数などのチェック
        if ! ./vendor/bin/phpmd $FILE text codesize,design,naming,unusedcode; then
            IS_ERROR=1
        fi
        # PSR準拠なコードかチェック
        if ! ./vendor/bin/php-cs-fixer fix $FILE --config-file="./.php_cs" --dry-run -v --diff; then
            IS_ERROR=1
        fi
        if ! php -l $FILE; then
            IS_ERROR=1
        fi
    else
        IS_ERROR=1
    fi
done
 
# composer.jsonのバリデーション
if ! ./composer.phar validate; then
    IS_ERROR=1
fi
 
# テストを実行
#if ! ./vendor/bin/phpunit -c tests/phpunit.xml; then
#    echo "　　　　 ,、,,,、,,, "
#    echo "　　　 _,,;' '\" '' ;;,, "
#    echo "　　（rヽ,;''\"\"''゛゛;,ﾉｒ）　　　　 "
#    echo "　　 ,; i ___　、___iヽ゛;,　　テスト書いてないとかお前それ@t_wadaの前でも同じ事言えんの？"
#    echo "　 ,;'''|ヽ・〉〈・ノ |ﾞ ';, "
#    echo "　 ,;''\"|　 　▼　　 |ﾞ゛';, "
#    echo "　 ,;''　ヽ ＿人＿  /　,;'_ "
#    echo " ／ｼ、    ヽ  ⌒⌒  /　 ﾘ　＼ "
#    echo "|　　 \"ｒ,,｀\"'''ﾞ´　　,,ﾐ| "
#    echo "|　　 　 ﾘ、　　　　,ﾘ　　 | "
#    echo "|　　i 　゛ｒ、ﾉ,,ｒ\" i _ | "
#    echo "|　　｀ー――-----------┴ ⌒´ ） "
#    echo "（ヽ  _____________ ,, ＿´） "
#    echo " （_⌒_______________ ,, ィ "
#    echo "     T                 |"
#    echo "     |                 |"
# 
#    IS_ERROR=1
#fi
 
exit $IS_ERROR


