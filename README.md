# test-clone
clone repository of test branch

\cp -rfp eviweb ./vendor/
php composer.phar install 

cp eviweb/fuelphp-phpcs/Standards/FuelPHP/ruleset.xml vendor/eviweb/fuelphp-phpcs/Standards/FuelPHP/
./vendor/bin/static-review.php hook:install hooks/test.pre-commit.php .git/hooks/pre-commit

